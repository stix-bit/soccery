<?php

namespace App\Http\Controllers;

use App\Mail\OrderReceiptMail;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\Product;
use Dompdf\Dompdf;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use RuntimeException;

class CheckoutController extends Controller
{
    private const CART_SESSION_KEY = 'cart';

    public function addToCart(Request $request, Product $product): RedirectResponse
    {
        $validated = $request->validate([
            'quantity' => ['required', 'integer', 'min:1'],
        ]);

        $this->ensureCustomer();

        if ((int) $product->stock < (int) $validated['quantity']) {
            return back()->with('error', 'Requested quantity is greater than available stock.');
        }

        $cart = $this->getCart();
        $currentQuantity = (int) ($cart[$product->id] ?? 0);
        $newQuantity = $currentQuantity + (int) $validated['quantity'];

        if ($newQuantity > (int) $product->stock) {
            return back()->with('error', 'Cannot add more than the available stock.');
        }

        $cart[$product->id] = $newQuantity;
        session()->put(self::CART_SESSION_KEY, $cart);

        return redirect()->route('cart.index')->with('status', 'Product added to cart.');
    }

    public function cart(): View
    {
        $this->ensureCustomer();

        [$items, $total] = $this->buildCartItemsAndTotal();

        return view('cart.index', [
            'items' => $items,
            'total' => $total,
        ]);
    }

    public function updateCart(Request $request, Product $product): RedirectResponse
    {
        $validated = $request->validate([
            'quantity' => ['required', 'integer', 'min:1'],
        ]);

        $this->ensureCustomer();

        $quantity = (int) $validated['quantity'];

        if ($quantity > (int) $product->stock) {
            return back()->with('error', 'Requested quantity is greater than available stock.');
        }

        $cart = $this->getCart();
        if (! isset($cart[$product->id])) {
            return back()->with('error', 'Product is not in your cart.');
        }

        $cart[$product->id] = $quantity;
        session()->put(self::CART_SESSION_KEY, $cart);

        return back()->with('status', 'Cart updated successfully.');
    }

    public function removeFromCart(Product $product): RedirectResponse
    {
        $this->ensureCustomer();

        $cart = $this->getCart();
        unset($cart[$product->id]);
        session()->put(self::CART_SESSION_KEY, $cart);

        return back()->with('status', 'Product removed from cart.');
    }

    public function showCheckout(): View|RedirectResponse
    {
        $this->ensureCustomer();

        [$items, $total] = $this->buildCartItemsAndTotal();
        if (empty($items)) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        return view('checkout.index', [
            'items' => $items,
            'total' => $total,
        ]);
    }

    public function checkout(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'payment_method' => ['required', 'in:online,cash_on_delivery'],
        ]);

        $this->ensureCustomer();

        [$items, $total] = $this->buildCartItemsAndTotal();
        if (empty($items)) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        $user = Auth::user();

        try {
            $order = DB::transaction(function () use ($items, $validated, $user) {
                $newOrder = Order::create([
                    'user_id' => $user->id,
                    'status' => 'pending',
                    'payment_method' => $validated['payment_method'],
                ]);

                $productIds = array_map(static fn (array $item): int => (int) $item['product']->id, $items);
                $lockedProducts = Product::whereIn('id', $productIds)
                    ->lockForUpdate()
                    ->get()
                    ->keyBy('id');

                foreach ($items as $item) {
                    $productId = (int) $item['product']->id;
                    $quantity = (int) $item['quantity'];
                    $lockedProduct = $lockedProducts->get($productId);

                    if (! $lockedProduct || (int) $lockedProduct->stock < $quantity) {
                        throw new RuntimeException('One or more items no longer have enough stock. Please review your cart.');
                    }

                    OrderProduct::create([
                        'order_id' => $newOrder->id,
                        'product_id' => $productId,
                        'quantity' => $quantity,
                    ]);

                    $lockedProduct->decrement('stock', $quantity);
                }

                return $newOrder;
            });
        } catch (RuntimeException $e) {
            return redirect()->route('cart.index')->with('error', $e->getMessage());
        }

        $order->load(['items.product.brand', 'items.product.category', 'user']);

        $pdfHtml = view('pdf.receipt', [
            'order' => $order,
            'items' => $items,
            'total' => $total,
        ])->render();

        $dompdf = new Dompdf();
        $dompdf->loadHtml($pdfHtml);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        $pdfContent = $dompdf->output();
        $pdfFileName = 'receipt-order-'.$order->id.'.pdf';
        $pdfRelativePath = 'receipts/'.$pdfFileName;
        Storage::disk('local')->makeDirectory('receipts');
        Storage::disk('local')->put($pdfRelativePath, $pdfContent);
        $pdfPath = Storage::disk('local')->path($pdfRelativePath);

        Mail::to($user->email)->send(new OrderReceiptMail($order, $items, $total, $pdfPath, $pdfFileName));

        session()->forget(self::CART_SESSION_KEY);

        return redirect()->route('user.orders')->with('status', 'Transaction completed! A receipt PDF has been emailed to you.');
    }

    public function myOrders(): View
    {
        $this->ensureCustomer();

        $orders = Order::with(['items.product.images'])
            ->where('user_id', Auth::id())
            ->latest()
            ->get();

        return view('user.order', [
            'orders' => $orders,
        ]);
    }

    private function ensureCustomer(): void
    {
        if (Auth::user()?->role !== 'customer') {
            abort(403);
        }
    }

    private function getCart(): array
    {
        $cart = session()->get(self::CART_SESSION_KEY, []);

        if (! is_array($cart)) {
            return [];
        }

        return $cart;
    }

    private function buildCartItemsAndTotal(): array
    {
        $cart = $this->getCart();
        if (empty($cart)) {
            return [[], 0.0];
        }

        $products = Product::with(['brand', 'category', 'images'])
            ->whereIn('id', array_keys($cart))
            ->get()
            ->keyBy('id');

        $items = [];
        $total = 0.0;

        foreach ($cart as $productId => $quantity) {
            $product = $products->get((int) $productId);
            if (! $product) {
                continue;
            }

            $qty = max(1, (int) $quantity);
            if ($qty > (int) $product->stock) {
                $qty = (int) $product->stock;
            }

            if ($qty < 1) {
                continue;
            }

            $lineTotal = (float) $product->price * $qty;
            $total += $lineTotal;

            $items[] = [
                'product' => $product,
                'quantity' => $qty,
                'line_total' => $lineTotal,
            ];
        }

        return [$items, $total];
    }
}


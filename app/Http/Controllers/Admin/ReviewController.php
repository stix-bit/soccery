<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\ReviewDataTable;
use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\RedirectResponse;

class ReviewController extends Controller
{
    public function index(ReviewDataTable $dataTable)
    {
        return $dataTable->render('admin.review.index');
    }

    public function destroy(Review $review): RedirectResponse
    {
        if (! $review->trashed()) {
            $review->delete();
        }

        return redirect()->route('admin.reviews.index')
            ->with('status', 'Review archived successfully.');
    }

    public function restore(int $review): RedirectResponse
    {
        $model = Review::withTrashed()->findOrFail($review);

        if ($model->trashed()) {
            $model->restore();
        }

        return redirect()->route('admin.reviews.index')
            ->with('status', 'Review restored successfully.');
    }

    public function forceDestroy(int $review): RedirectResponse
    {
        $model = Review::withTrashed()->findOrFail($review);
        $model->forceDelete();

        return redirect()->route('admin.reviews.index')
            ->with('status', 'Review permanently deleted.');
    }
}

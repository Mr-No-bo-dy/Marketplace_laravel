<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    /**
     * Display a listing of the Reviews.
     *
     * @return View
     */
    public function index(): View
    {
        $reviews = Review::with(['client', 'product', 'product.seller'])->has('product')->paginate(30);
        $statuses = [
            1 => trans('admin/reviews.status1'),
            2 => trans('admin/reviews.status2'),
        ];

        $reviews->getCollection()->transform(fn ($review) => $this->addReviewDetails($review, $statuses));

        return view('admin.reviews.index', compact('reviews'));
    }

    /**
     * @param Review $review
     * @param array $statuses
     * @return Review
     */
    public function addReviewDetails(Review $review, array $statuses): Review
    {
        $review->status_label = $statuses[$review->status] ?? $review->status;

        $review->id_client = $review->client->id_client;
        $review->client_name = $review->client->name;
        $review->client_surname = $review->client->surname;

        $review->id_seller = $review->product->seller->id_seller;
        $review->seller_name = $review->product->seller->name;
        $review->seller_surname = $review->product->seller->surname;

        $review->id_product = $review->product->id_product;
        $review->product_name = $review->product->name;

        return $review;
    }

    /**
     * Change Review's status or Delete Review if Product was soft_deleted.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function change(Request $request): RedirectResponse
    {
        $idReview = $request->validate(['id_review' => ['bail', 'required', 'integer', 'min:1', 'max:999999999']])['id_review'];
        $review = Review::findOrFail($idReview);

            // Approve
        if ($request->has('approveReview') && $review->status == 1) {
            $review->update(['status' => 2]);

            // Disapprove
        } elseif ($request->has('disapproveReview') && $review->status == 2) {
            $review->update(['status' => 1]);

            // Delete
        } elseif ($request->has('deleteReview')) {
            $review->forceDelete();
        }

        return back();
    }
}

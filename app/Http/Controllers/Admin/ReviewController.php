<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Site\Review;
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
        $reviews = Review::all();

        $statuses = [
            1 => trans('admin/reviews.status1'),
            2 => trans('admin/reviews.status2'),
        ];

        foreach ($reviews as $id => $review) {
            if (isset($review->product)) {
                $review->status_id = $review->status;
                $review->status = $statuses[$review->status];
                $review->client_id = $review->client->id_client;
                $review->client_name = $review->client->name;
                $review->client_surname = $review->client->surname;
                $review->seller_id = $review->product->seller->id_seller;
                $review->seller_name = $review->product->seller->name;
                $review->seller_surname = $review->product->seller->surname;
                $review->product_id = $review->product->id_product;
                $review->product_name = $review->product->name;
                $review->product_url = route('product.show', $review->product->id_product);
            }
        }

        return view('admin.reviews.index', compact('reviews'));
    }

    /**
     * Change Review's status or Delete Review if Product was soft_deleted.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function change(Request $request): RedirectResponse
    {
        $idReview = $request->validate(['id_review' => ['bail', 'integer', 'min: 1', 'max:9223372036854775807']])['id_review'];
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

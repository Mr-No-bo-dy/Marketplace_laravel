<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Site\Review;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    /**
     * Display a listing of the Reviews.
     */
    public function index()
    {
        $reviews = Review::all();

        $statuses = [
            1 => trans('admin/reviews.status1'),
            2 => trans('admin/reviews.status2'),
        ];

        foreach ($reviews as $review) {
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

        return view('admin.reviews.index', compact('reviews'));
    }

    /**
     * Change Review's status.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function change(Request $request): RedirectResponse
    {
        $idReview = $request->post('id_review');
        $reviewStatus = Review::find($idReview)->status;
        if ($request->has('approveReview') && $reviewStatus == 1) {
            Review::where('id_review', $idReview)->update(['status' => 2]);
        } elseif ($request->has('unapproveReview') && $reviewStatus == 2) {
            Review::where('id_review', $idReview)->update(['status' => 1]);
        }

        return back();
    }
}

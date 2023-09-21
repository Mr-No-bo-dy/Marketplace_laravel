<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Site\Review;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    /**
     * Create Review
     */
    public function store(Request $request): RedirectResponse
    {
        $reviewModel = new Review();

        if (!$request->session()->has('id_client')) {
            return back();
        }

        if ($request->has('addReview')) {
            $review = [
                'id_client' => $request->session()->get('id_client'),
                'id_product' => $request->post('id_product'),
                'comment' => $request->post('comment'),
                'rating' => $request->post('rating'),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ];

            $reviewModel->insert($review);
        }

        return back();
    }

    /**
     * Update Review
     */
    public function update(Request $request): RedirectResponse
    {
        $idReview = $request->post('id_review');
        if ($request->post('reviewEdit')) {
            $request->session()->put('reviewEditIdReview', $idReview);

        } elseif ($request->has('reviewUpdate')) {
            $idReview = $request->post('id_review');
            $reviewUpdateData = [
                'comment' => $request->post('comment'),
                'rating' => $request->post('rating'),
            ];
            Review::where('id_review', $idReview)->update($reviewUpdateData);
            $request->session()->forget('reviewEditIdReview');

        } elseif ($request->has('reviewCancel')) {
            $request->session()->forget('reviewEditIdReview');
        }

        return back();
    }

    /**
     * Delete Review
     */
    public function destroy(Request $request): RedirectResponse
    {
        if ($request->has('reviewDelete')) {
            $idReview = $request->post('id_review');
            Review::destroy($idReview);
        }

        return back();
    }
}

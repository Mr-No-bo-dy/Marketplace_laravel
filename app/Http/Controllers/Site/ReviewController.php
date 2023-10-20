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
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        if (!$request->session()->has('id_client')) {
            return back();
        }

        if ($request->has('addReview')) {
            $reviewModel = new Review();

            $setReviewData = [
                'id_client' => $request->session()->get('id_client'),
                'id_product' => $request->post('id_product'),
                'comment' => $request->post('comment'),
                'rating' => $request->post('rating'),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ];
            $reviewModel->storeReview($setReviewData);
        }

        return back();
    }

    /**
     * Update Review
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function update(Request $request): RedirectResponse
    {
        $idReview = $request->post('id_review');
        if ($request->has('editReview')) {
            $request->session()->put('editReviewId', $idReview);

        } elseif ($request->has('updateReview')) {
            $reviewModel = new Review();

            $setReviewData = [
                'comment' => $request->post('comment'),
                'rating' => $request->post('rating'),
            ];
            $reviewModel->updateReview($idReview, $setReviewData);
            $request->session()->forget('editReviewId');

        } elseif ($request->has('cancelReview')) {
            $request->session()->forget('editReviewId');
        }

        return back();
    }

    /**
     * Delete Review
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function destroy(Request $request): RedirectResponse
    {
        if ($request->has('deleteReview')) {
            $reviewModel = new Review();

            $idReview = $request->post('id_review');
            $reviewModel->destroyReview($idReview);
        }

        return back();
    }
}

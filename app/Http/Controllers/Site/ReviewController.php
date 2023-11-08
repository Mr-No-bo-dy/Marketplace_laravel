<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Http\Requests\ReviewRequest;
use App\Models\Site\Review;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    /**
     * Create Review
     *
     * @param ReviewRequest $request
     * @return RedirectResponse
     */
    public function store(ReviewRequest $request): RedirectResponse
    {
        if (!$request->session()->has('id_client')) {
            return back();
        }

        if ($request->has('addReview')) {
            $reviewModel = new Review();

            $additionalReviewData = [
                'id_client' => $request->session()->get('id_client'),
                'id_product' => $request->post('id_product'),
            ];
            $reviewModel->storeReview(array_merge($additionalReviewData, $request->validated()));
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

            $validatedReview = $request->validate([
                'comment' => ['required', 'string', 'max:511'],
                'rating' => ['required', 'int', 'min:1', 'max:5'],
            ]);
            $reviewModel->updateReview($idReview, array_merge($validatedReview, ['status' => 1]));

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

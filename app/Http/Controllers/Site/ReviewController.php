<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Http\Requests\ReviewRequest;
use App\Models\Review;
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
            return redirect()->route('auth');
        }

        $idProduct = $request->validate(['id_product' => ['bail', 'required', 'integer', 'min:1', 'max:999999999']])['id_product'];
        $additionalReviewData = [
            'id_client' => $request->session()->get('id_client'),
            'id_product' => $idProduct,
        ];
        Review::create(array_merge($additionalReviewData, $request->validated()));

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
        $idReview = $request->validate(['id_review' => ['bail', 'required', 'integer', 'min:1', 'max:999999999']])['id_review'];
        if ($request->has('editReview')) {
            $request->session()->put('editReviewId', $idReview);

        } elseif ($request->has('updateReview')) {
            $review = Review::findOrFail($idReview);
            if ($request->session()->has('id_client') &&
                $request->session()->get('id_client') == $review->id_client) {
                $validatedReview = $request->validate([
                    'comment' => ['required', 'string', 'max:511'],
                    'rating' => ['required', 'int', 'min:1', 'max:5'],
                ]);
                $review->update(array_merge($validatedReview, ['status' => 2]));
                $request->session()->forget('editReviewId');

            } else {
                abort(403, 'Unauthorized action.');
            }

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
        $idReview = $request->validate(['id_review' => ['bail', 'required', 'integer', 'min:1', 'max:999999999']])['id_review'];
        $review = Review::findOrFail($idReview);

        if ($request->session()->has('id_client') &&
            $request->session()->get('id_client') == $review->id_client) {
            $review->delete();
        } else {
            abort(403, 'Unauthorized action.');
        }

        return back();
    }
}

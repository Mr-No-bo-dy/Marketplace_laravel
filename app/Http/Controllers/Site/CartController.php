<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Http\Services\CartService;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class CartController extends Controller
{
    private CartService $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    /**
     * Displaying listing of Cart.
     *
     * @return View
     */
    public function index(): View
    {
        extract($this->cartService->getCartData());

        return view('site.cart.index', compact('products', 'marketsData', 'totalQuantity'));
    }

    /**
     * Adding Products to Cart.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        $idProduct = $request->validate([
            'id_product' => ['bail','required','integer','exists:App\Models\Product,id_product']
        ])['id_product'];

        $product = Product::with(['media', 'seller.marketplace:id_marketplace,currency'])->findOrFail($idProduct);

        if ($product) $this->cartService->add($product);

        return back();
    }

    /**
     * Updating listing of Cart.
     *
     * @param Request $request
     * @param int $idProduct
     * @return RedirectResponse
     */
    public function update(Request $request, int $idProduct): RedirectResponse
    {
        $validated = $request->validate([
            'quantity' => ['required','integer','min:0','max:999999'],
            'action_type' => ['nullable','string','max:32'],
        ]);

        $this->cartService->update($idProduct, $validated['quantity'], $validated['action_type'] ?? '');

        return back();
    }

    /**
     * Remove product from Cart.
     *
     * @param int $idProduct
     * @return RedirectResponse
     */
    public function delete(int $idProduct): RedirectResponse
    {
        $this->cartService->remove($idProduct);

        return back();
    }
}

<?php

namespace App\Http\Services;

use App\Models\Product;

class CartService
{
    protected string $sessionKey = 'cart.products';

    /**
     * Return structured cart data for view
     *
     * @return array
     */
    public function getCartData(): array
    {
        $products = collect(session($this->sessionKey, []))->map(function ($product) {
            return (object) $product;
        });

        // Formating market's data
        $marketsRaw = session('cart.markets', []);
        $marketsData = array_map(function ($market) {
            return [
                'quantity' => $market['quantity'] ?? 0,
                'total' => $market['total'] ?? 0,
                'currency' => $market['currency'] ?? '',
                'totalFormatted' => number_format($market['total'] ?? 0, 0, '.', ' ') .
                    ' ' . ($market['currency'] ?? ''),
            ];
        }, $marketsRaw);

        $totalQuantity = session('cart.total_quantity', 0);

        return [
            'products' => $products,
            'marketsData'  => $marketsData,
            'totalQuantity' => $totalQuantity,
        ];
    }

    /**
     * Add product to Cart or increment its quantity.
     *
     * @param Product $product
     * @param int $quantity
     * @return void
     */
    public function add(Product $product, int $quantity = 1): void
    {
        // Ensure relationships are available
        if (!$product->relationLoaded('seller') ||
            !$product->seller->relationLoaded('marketplace') ||
            !$product->relationLoaded('media')) {
            $product->loadMissing(['media', 'seller.marketplace']);
        }

        $cartItem = session($this->sessionKey . '.' . $product->id_product);
        $newQuantity = $cartItem ? $cartItem['quantity'] + $quantity : $quantity;
        $imageUrl = $product->getFirstMediaUrl('products')
            ? $product->getFirstMediaUrl('products')
            : asset('/images/_no_photo.svg');

        session()->put($this->sessionKey . '.' . $product->id_product, [
            'id_marketplace' => $product->seller->marketplace->id_marketplace,
            'id_seller' => $product->id_seller,
            'id_product' => $product->id_product,
            'name' => $product->name,
            'quantity' => $newQuantity,
            'price' => $product->price,
            'cost' => $newQuantity * $product->price,
            'currency' => $product->seller->marketplace->getCurrency(),
            'img_url' => $imageUrl,
        ]);

        $this->updateCartData();
    }

    /**
     * Update product's quantity (set absolute), or remove if quantity <= 0.
     *
     * @param int $idProduct
     * @param int $quantity
     * @param string $action
     * @return void
     */
    public function update(int $idProduct, int $quantity, string $action = ''): void
    {
        $cartItem = session($this->sessionKey . '.' . $idProduct);

        if (!$cartItem) {
            return;
        }

        if ($quantity <= 0) {
            $this->remove($idProduct);
            return;
        }

        $newQuantity = match ($action) {
            'plus' => $cartItem['quantity'] + 1,
            'minus' => max(1, $cartItem['quantity'] - 1),
            default => max(1, $quantity),
        };

        $cartItem['quantity'] = $newQuantity;
        $cartItem['cost'] = $cartItem['price'] * $newQuantity;

        session()->put($this->sessionKey . '.' . $idProduct, $cartItem);

        $this->updateCartData();
    }

    /**
     * Remove product from Cart.
     *
     * @param int $idProduct
     * @return void
     */
    public function remove(int $idProduct): void
    {
        session()->forget($this->sessionKey . '.' . $idProduct);

        $this->updateCartData();
    }

    /**
     * Recalculate markets totals and overall quantity after any change.
     *
     * @return void
     */
    protected function updateCartData(): void
    {
        $cart = collect(session($this->sessionKey, []));
        $marketsTotal = $cart->groupBy('id_marketplace')->map(function ($products) {
            return [
                'quantity' => $products->sum('quantity'),
                'total'    => $products->sum('cost'),
                'currency' => $products->first()['currency'] ?? '',
            ];
        })->toArray();

        session()->put('cart.markets', $marketsTotal);
        session()->put('cart.total_quantity', $cart->sum('quantity'));
    }
}

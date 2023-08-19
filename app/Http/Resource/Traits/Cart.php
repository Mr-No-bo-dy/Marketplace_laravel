<?php

namespace App\Http\Resource\Traits;

use App\Models\Site\Product;
use Illuminate\Http\Request;

trait Cart
{
    /**
     * Setting the Cart data for view.
     */
    public function getCartData(Request $request): array
    {
        $idsProduct = $productData = [];
        $cartData = $request->session()->get('cart');
        foreach ($cartData['product'] as $product) {
            $idsProduct[$product['id_product']] = $product['id_product'];
            $productData[$product['id_product']]['quantity'] = $product['quantity'];
            $productData[$product['id_product']]['total'] = $product['total'];
        }
        $total =  !empty($request->session()->get('cart.product')) ? request()->session()->get('cart.total') : [];

        $products = !empty($request->session()->get('cart.product')) ? Product::whereIn('id_product', $idsProduct)->get() : [];

        return [
            'productData' => $productData,
            'products' => $products,
            'total' => $total,
        ];
    }
}

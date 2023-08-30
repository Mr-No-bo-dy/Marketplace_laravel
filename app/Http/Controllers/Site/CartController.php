<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resource\Traits\Cart;

class CartController extends Controller
{
    use Cart;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if (!$request->session()->has('cart')) {
            return view('site.cart.index');
        }

        $idProduct = $request->post('id_product');
        $product = $request->session()->get('cart.product.' . $idProduct);
        $total = $request->session()->get('cart.total');

        // If form with changes in Cart is sent
        if (!empty($request->post('id_product'))) {

            if (!is_null($product)) {
                $newProduct = [
                    'id_product' => $product['id_product'],
                    'quantity' => $product['quantity'],
                    'price' => $product['price'],
                    'total' => $product['total'],
                ];
            }

            $newQuantity = null;
            if ($request->post('quantity')) {
                $newQuantity = $request->post('quantity');
                $total['quantity'] = $total['quantity'] - $product['quantity'] + $newQuantity;
                $total['total'] = ($total['total'] - $product['total']) + ($newQuantity * $product['price']);
            } elseif ($request->post('plus')) {
                $newQuantity = $product['quantity'] + 1;
                $total['quantity'] += 1;
                $total['total'] += $product['price'];
            } elseif ($request->post('minus') && !empty($product)) {
                $newQuantity = $product['quantity'] - 1;
                if ($newQuantity <= 0) {
                    $request->session()->forget('cart.product.' . $idProduct);
                    unset($idProduct);
                }
                $total['quantity'] -= 1;
                $total['total'] -= $product['price'];
            } elseif ($request->post('remove')) {
                if (!is_null($product)) {
                    $total['quantity'] = $total['quantity'] - $product['quantity'];
                    $total['total'] = $total['total'] - $product['total'];
                }
                $request->session()->forget('cart.product.' . $idProduct);
                unset($idProduct);
            }

            // Setting new Cart data into session Cart
            if (isset($idProduct) && !empty($product)) {
                $newProduct['quantity'] = $newQuantity;
                $newProduct['total'] = $newQuantity * $product['price'];
                $request->session()->put('cart.product.' . $idProduct, $newProduct);
            }
            $request->session()->put('cart.total', $total);
        }

        // Setting data for view
        extract($this->getCartData($request));

        return view('site.cart.index', compact('products', 'productData', 'total'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}

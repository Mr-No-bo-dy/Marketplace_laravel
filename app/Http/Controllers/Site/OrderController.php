<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Site\Order;
use App\Models\Site\Product;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $idsProduct = $productData = [];
        $cartData = $request->session()->get('cart');
        foreach ($cartData['product'] as $product) {
            $idsProduct[$product['id_product']] = $product['id_product'];
            $productData[$product['id_product']]['quantity'] = $product['quantity'];
            $productData[$product['id_product']]['total'] = $product['total'];
        }
        $total =  !empty($request->session()->get('cart.product')) ? request()->session()->get('cart.total') : [] ;

        $products = !empty($request->session()->get('cart.product')) ? Product::whereIn('id_product', $idsProduct)->get() : [];

        return view('site.order.index', compact('products', 'productData', 'total'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $orderModel = new Order();

        $data = [
            'id_product' => $request->post('id_product'),
            // 'id_client' => $request->session()->get('id_client') ?: "(create new client)",
            'name' => $request->post('name'),
            'price' => $request->post('price'),
        ];

        $orderModel->storeOrder($data);
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
    public function show(Order $order)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Order $order)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Order $order)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order)
    {
        //
    }
}

<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Http\Requests\ClientRequest;
use App\Models\Site\Client;
use App\Models\Site\Order;
use Illuminate\Http\Request;
use App\Http\Resource\Traits\Cart;

class OrderController extends Controller
{
    use Cart;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        extract($this->getCartData($request));

        return view('site.order.index', compact('products', 'productData', 'total'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(ClientRequest $request)
    {
        $orderModel = new Order();
        $clientModel = new Client();

        $clientModel->fill($request->validated())
                    ->save();
        $idNewClient = Client::latest()->first()->id_client;

        $cartData = $request->session()->get('cart');
        foreach ($cartData['product'] as $product) {
            $orderData = [
                'id_client' => $idNewClient,
                'id_seller' => $product['id_seller'],
                'status' => 1,
                'date' => date('Y-m-d H:i:s'),
                // 'id_client' => $request->session()->get('id_client') ?: "(create new client)",
            ];
            //            dd($orderData);
            $orderModel->fill($orderData)
                        ->save();
            $idNewOrder = Order::latest()->first()->id_order;
            $orderDetails = [
                'id_order' => $idNewOrder,
                'id_product' => $product['id_product'],
                'count' => $product['total'],
            ];
            $orderModel->storeOrderDetails($orderDetails);
            //            $orderModel->orderDetails->fill($orderDetails)->save();

        }
        //        $orderModel->storeOrder($data);
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

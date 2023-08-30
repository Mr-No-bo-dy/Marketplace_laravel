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
     * Show the form for creating a new Order.
     */
    public function create(ClientRequest $request)
    {
        extract($this->getCartData($request));

        $client = Client::find($request->session()->get('id_client'));

        return view('site.order.index', compact('products', 'productData', 'total', 'client'));
    }

    /**
     * Store a newly created Order in storage.
     */
    public function store(ClientRequest $request)
    {
        $orderModel = new Order();

        /** Register new client or take existing:
         * If a customer with the given email doesn't exist, create a new one;
         * otherwise, only retrieve the ID of the existing customer.
         */
        /**
         * Тут проблема в тому, що новий клієнт реєструється в БД без аккаунта, але з імейлом.
         * В такому випадку без функціоналу зв'язку з імейлом
         * не можливо зареєструвати аккаунт клієнта на вже зайнятий імейл.
         */
        $client = Client::firstOrCreate([
            'email' => $request->post('email'),
        ],
        [
            'phone' => $request->post('phone'),
            'name' => $request->post('name'),
            'surname' => $request->post('surname'),
        ]);

        // Additionally, if the existing customer's data from the form is different, update it in the DB.
        $client->email = $request->post('email');
        $client->phone = $request->post('phone');
        $client->name = $request->post('name');
        $client->surname = $request->post('surname');
        if ($client->isDirty()) {
            $client->save();
        }
        $idClient = $client->id_client;

        // Forming Order's data
        $cartData = $request->session()->get('cart');
        if (!empty($cartData)) {
            foreach ($cartData['product'] as $product) {
                $orderData = [
                    'id_client' => $idClient,
                    'id_seller' => $product['id_seller'],
                    'status' => 'new',
                    'date' => date('Y-m-d H:i:s'),
                ];
                $orderModel->fill($orderData)
                            ->save();

                $idNewOrder = Order::latest()->first()->id_order;
                $orderDetailsData = [
                    'id_order' => $idNewOrder,
                    'id_product' => $product['id_product'],
                    'count' => $product['quantity'],
                    'total' => $product['total'],
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ];
                $orderModel->storeOrderDetails($orderDetailsData);
            }
            $request->session()->forget('cart');
        }

        return view('site.templates.order-done');
    }

    /**
     * Display a listing of the Orders.
     */
    public function index(Request $request)
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

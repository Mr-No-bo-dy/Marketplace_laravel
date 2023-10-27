<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Http\Requests\ClientRequest;
use App\Http\Resource\Traits\Cart;
use App\Models\Site\Client;
use App\Models\Site\Order;
use App\Models\Site\OrderDetails;
use App\Models\Site\Seller;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    use Cart;

    /**
     * Display a listing of the Orders to given Seller.
     *
     * @param Request $request
     * @return View
     */
    public function index(Request $request): View
    {
        $sellerModel = new Seller();
        $orderModel = new Order();

        $idSeller = $request->session()->get('id_seller');
        $seller = $sellerModel->readSeller($idSeller);
        $orders = $orderModel->readSellerOrdersWithDetails($idSeller);

        return view('site.seller.orders', compact('seller', 'orders'));
    }

    /**
     * Show the form for creating a new Order.
     *
     * @param ClientRequest $request
     * @return View
     */
    public function create(ClientRequest $request): View
    {
        $clientModel = new Client();

        $idClient = $request->session()->get('id_client');
        if ($idClient) {
            $client = $clientModel->readClient($idClient);
        } else {
            $client = null;
        }

        extract($this->getCartData($request));

        return view('site.order.index', compact('client', 'products', 'cartProductsData', 'cartMarketsData', 'totalQuantity'));
    }

    /**
     * Store a newly created Order in storage.
     *
     * @param ClientRequest $request
     * @return View
     */
    public function store(ClientRequest $request): View
    {
        $orderModel = new Order();
        $orderDetailsModel = new OrderDetails();

        /** Register new client or use existing:
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
            'phone' => preg_replace("#[^0-9]#", "", $request->post('tel')),
            'name' => $request->post('name'),
            'surname' => $request->post('surname'),
        ]);

        // Additionally, if the existing customer's data from the form is different, update it in the DB.
        $client->email = $request->post('email');
        $client->phone = preg_replace("#[^0-9]#", "", $request->post('tel'));
        $client->name = $request->post('name');
        $client->surname = $request->post('surname');
        if ($client->isDirty()) {
            $client->save();
        }
        $idClient = $client->id_client;

        // Forming Order's data
        $cartData = $request->session()->get('cart');
        if (!empty($cartData) && $request->has('makeOrder')) {
            foreach ($cartData['products'] as $product) {
                $orderData = [
                    'id_client' => $idClient,
                    'id_seller' => $product['id_seller'],
                    'status' => 'new',
                    'date' => date('Y-m-d H:i:s'),
                ];
                $orderModel->fill($orderData)
                            ->save();

                $idNewOrder = $orderModel->getLastOrderId();
                $orderDetailsData = [
                    'id_order' => $idNewOrder,
                    'id_product' => $product['id_product'],
                    'count' => $product['quantity'],
                    'total' => $product['total'],
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ];
                $orderDetailsModel->storeOrderDetails($orderDetailsData);
            }
            $request->session()->forget('cart');
        }

        return view('site.templates.order-done');
    }

    /**
     * Update active Orders by given Seller.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function update(Request $request): RedirectResponse
    {
        $orderModel = new Order();

        $idOrder = $request->post('id_order');
        if ($request->has('order_accept')) {
            $orderModel->updateSellerOrders($idOrder, ['status' => 'processed']);
        } elseif ($request->has('order_decline')) {
            $orderModel->updateSellerOrders($idOrder, ['status' => 'declined']);
        }

        return redirect()->route('order.my_orders');
    }
}

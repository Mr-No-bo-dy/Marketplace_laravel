<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Http\Requests\ClientRequest;
use App\Http\Services\CartService;
use App\Models\Client;
use App\Models\Order;
use App\Models\OrderDetails;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    private CartService $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    /**
     * Display a listing of the Orders to given Seller.
     *
     * @return View
     */
    public function index(): View
    {
        $orderModel = new Order();

        $orders = $orderModel->readSellerOrdersWithDetails(session('id_seller'));

        return view('site.seller.orders', compact('orders'));
    }

    /**
     * Show the form for creating a new Order.
     *
     * @param Request $request
     * @return View|RedirectResponse
     */
    public function create(Request $request): View|RedirectResponse
    {
        $client = new Client;

        $idClient = $request->session()->get('id_client');
        if ($idClient) $client = Client::findOrFail($idClient);

        extract($this->cartService->getCartData());

        return view('site.order.index', compact('client', 'marketsData', 'totalQuantity'));
    }

    /**
     * Store a newly created Order in storage.
     *
     * @param ClientRequest $request
     * @return View
     */
    public function store(ClientRequest $request): View
    {
        $idClient = $this->saveClient($request);

        $cartData = $request->session()->get('cart');
        if (!empty($cartData) && !empty($cartData['products'])) {
            $productsBySeller = collect($cartData['products'])->groupBy('id_seller');
            foreach ($productsBySeller as $idSeller => $products) {
                // Saving Order
                $idOrder = Order::create([
                    'id_client' => $idClient,
                    'id_seller' => $idSeller,
                    'status' => 'pending',
                ])->id_order;

                // Saving Order's Details
                $orderDetails = $products->map(function ($product) use ($idOrder) {
                    return [
                        'id_order' => $idOrder,
                        'id_product' => $product['id_product'],
                        'count' => $product['quantity'],
                        'total' => $product['cost'],
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                })->all();
                OrderDetails::insert($orderDetails);
            }

            $request->session()->forget('cart');
        }

        return view('site.templates.order-done');
    }

    /**
     * Register new client or update existing.
     *
     * @param ClientRequest $request
     * @return int
     */
    public function saveClient(ClientRequest $request): int
    {
        $validated = $request->validated();

        $idClient = session('id_client');
        if ($idClient) {
            Client::find($idClient)->update($validated);
            return $idClient;
        }

        return Client::create($validated)->id_client;
    }

    /**
     * Update active Orders to specific Seller.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function update(Request $request): RedirectResponse
    {
        $idOrder = $request->validate(['id_order' => ['bail', 'required', 'int', 'exists:orders']])['id_order'];
        if ($request->has('order_accept')) {
            Order::findOrFail($idOrder)->update(['status' => 'processed']);
        } elseif ($request->has('order_decline')) {
            Order::findOrFail($idOrder)->update(['status' => 'canceled']);
        }

        return redirect()->route('order.my_orders');
    }
}

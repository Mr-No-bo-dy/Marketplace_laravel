<?php

namespace App\Http\Controllers;

use App\Models\Admin\Marketplace;
use App\Models\Site\Client;
use App\Models\Site\Product;
use App\Models\Site\Seller;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class GeneralController extends Controller
{
    /**
     * Switch Language.
     */
    public function switchLanguage(Request $request)
    {
        return redirect()->back();
    }

    /**
     * Display site's Home page.
     */
    public function index()
    {
        return view('index');
    }

    /**
     * Show the form for Registering a new Seller.
     */
    public function register()
    {
        $marketplaces = Marketplace::all(['id_marketplace', 'country']);

        return view('authenticate.register', compact('marketplaces'));
    }

    /**
     * Show the form for Registering a new Client.
     */
    public function registerClient()
    {
        return view('authenticate.registerClient');
    }

    /**
     * Store a newly created Seller in storage.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        $sellerModel = new Seller();

        $postData = $request->post();
        $setSellerData = [
            'id_marketplace' => $postData['id_marketplace'],
            'name' => $postData['name'],
            'surname' => $postData['surname'],
            'email' => $postData['email'],
            'phone' => $postData['tel'],
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ];
        $idNewSeller = $sellerModel->storeSeller($setSellerData);

        $setSellerPasswordData = [
            'id_seller' => $idNewSeller,
            'password' => Hash::make($postData['password']),
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ];
        $sellerModel->storeSellerPassword($setSellerPasswordData);

        $request->session()->put('id_seller', $idNewSeller);

        return redirect()->route('auth');
    }

    /**
     * Store a newly created Client in storage.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function storeClient(Request $request): RedirectResponse
    {
        $clientModel = new Client();

        $postData = $request->post();
        $setClientData = [
            'name' => $postData['name'],
            'surname' => $postData['surname'],
            'email' => $postData['email'],
            'phone' => $postData['tel'],
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ];
        $idNewClient = $clientModel->storeClient($setClientData);

        $setClientPasswordData = [
            'id_client' => $idNewClient,
            'password' => Hash::make($postData['password']),
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ];
        $clientModel->storeClientPassword($setClientPasswordData);

        $request->session()->put('id_client', $idNewClient);

        return redirect()->route('auth');
    }

    /**
     * Login Seller into Personal Page.
     *
     * @param Request $request
     * @return View|RedirectResponse
     */
    public function auth(Request $request): View|RedirectResponse
    {
        $sellerModel = new Seller();
        $clientModel = new Client();

        if ($request->session()->has('id_seller')) {
            return redirect()->route('seller.personal');
        } elseif ($request->session()->has('id_client')) {
            return redirect()->route('client.personal');
        }

        $route = view('authenticate.login');

        $postData = $request->post();
        if (!empty($postData)) {
            $data = [
                'login' => $postData['login'],
                'password' => $postData['password'],
            ];

            $idAuthUser = $sellerModel->authSeller($data);
            if (!empty($idAuthUser)) {
                $seller = Seller::withTrashed()->find($idAuthUser);
                if (!$seller->trashed()) {
                    $request->session()->put('id_seller', $idAuthUser);
                    $route = redirect()->route('seller.personal');
                }
            } else {
                $idAuthUser = $clientModel->authClient($data);
                $request->session()->put('id_client', $idAuthUser);
                $route = redirect()->route('client.personal');
            }
        }

        return $route;
    }

    /**
     * Logout Seller and Client from Site.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function logout(Request $request): RedirectResponse
    {
        $request->session()->forget(['id_seller', 'id_client']);

        return redirect()->route('index');
    }

    /**
     * Adding Products to Cart.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function addToCart(Request $request): RedirectResponse
    {
        if ($request->post('addToCart')) {
            $idProduct = $request->post('id_product');
            $price = $request->post('price');
            $pr = Product::find($idProduct);
            $product = $request->session()->get('cart.product.' . $idProduct);

            $quantity = 1;
            $productTotal = $price;
            if (isset($product['id_product']) && $product['id_product'] == $idProduct) {
                $quantity = $product['quantity'] + 1;
                $productTotal = $quantity * $price;
            }
            $request->session()->put('cart.product.' . $idProduct, [
                'id_seller' => $pr->id_seller,
                'id_product' => $idProduct,
                'quantity' => $quantity,
                'price' => $price,
                'total' => $productTotal,
            ]);

            $cart = $request->session()->get('cart.product');
            if (!isset($cartTotal['quantity']) && !isset($cartTotal['total'])) {
                $cartTotal = [
                    'quantity' => 0,
                    'total' => 0,
                ];
            }
            foreach ($cart as $p) {
                $cartTotal['quantity'] += $p['quantity'];
                $cartTotal['total'] += $p['total'];
            }
            $request->session()->put('cart.total', $cartTotal);
        }

        return redirect()->route('product');
    }
}

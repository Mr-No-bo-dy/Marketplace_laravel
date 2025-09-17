<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Http\Requests\AuthRequest;
use App\Http\Requests\ClientRequest;
use App\Http\Requests\SellerRequest;
use App\Models\Client;
use App\Models\Marketplace;
use App\Models\Seller;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    /**
     * Show the form for Registering a new Seller.
     *
     * @return View
     */
    public function registerSeller(): View
    {
        $marketplaces = Marketplace::all(['id_marketplace', 'country']);

        return view('authenticate.register-seller', compact('marketplaces'));
    }

    /**
     * Show the form for Registering a new Client.
     *
     * @return View
     */
    public function registerClient(): View
    {
        return view('authenticate.register-client');
    }

    /**
     * Store a newly created Seller in storage.
     *
     * @param SellerRequest $request
     * @return RedirectResponse
     */
    public function storeSeller(SellerRequest $request): RedirectResponse
    {
        $sellerModel = new Seller();

        $idNewSeller = $sellerModel::create($request->safe()->except('password'))->id_seller;
        $sellerModel->storeSellerPassword($idNewSeller, $request->validated('password'));

        $request->session()->put('id_seller', $idNewSeller);

        return redirect()->route('auth');
    }

    /**
     * Store a newly created Client in storage.
     *
     * @param ClientRequest $request
     * @return RedirectResponse
     */
    public function storeClient(ClientRequest $request): RedirectResponse
    {
        $clientModel = new Client();

        $idNewClient = $clientModel::create($request->safe()->except('password'))->id_client;
        $clientModel->storeClientPassword($idNewClient, $request->validated('password'));

        $request->session()->put('id_client', $idNewClient);

        return redirect()->route('auth');
    }

    /**
     * Show view for Login Seller / Client or lead logged one to Personal Page.
     *
     * @return View|RedirectResponse
     */
    public function auth(): View|RedirectResponse
    {
        if (session()->has('id_seller')) {
            return redirect()->route('seller.personal');
        }

        if (session()->has('id_client')) {
            return redirect()->route('client.personal');
        }

        return view('authenticate.login');
    }

    /**
     * * Login Seller / Client.
     *
     * @param AuthRequest $request
     * @return View|RedirectResponse
     */
    public function authenticate(AuthRequest $request): View|RedirectResponse
    {
        $sellerModel = new Seller();
        $clientModel = new Client();

        $validated = $request->validated();

        // Auth Seller
        $idAuthUser = $sellerModel->authSeller($validated);
        if ($idAuthUser) {
            $request->session()->put('id_seller', $idAuthUser);
            return redirect()->route('seller.personal');
        }

        // Auth Client
        $idAuthUser = $clientModel->authClient($validated);
        if ($idAuthUser) {
            $request->session()->put('id_client', $idAuthUser);
            return redirect()->route('client.personal');
        }

        // Failed auth
        return back()->withErrors(['password' => trans('auth.failed')])->withInput();
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
}

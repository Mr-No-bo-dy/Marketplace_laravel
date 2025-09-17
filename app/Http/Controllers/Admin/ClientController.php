<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Client;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    /**
     * Display a listing of the Clients.
     *
     * @return View
     */
    public function index(): View
    {
        $clientModel = new Client();

        $clients = $clientModel->readAllClients();

        return view('admin.clients.index', compact('clients'));
    }

    /**
     * Block specified Client in storage.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function block(Request $request): RedirectResponse
    {
        $idClient = $request->validate(['id_client' => ['bail', 'required', 'integer', 'min:1', 'max:999999999']])['id_client'];
        $request->session()->forget('id_client');
        Client::findOrFail($idClient)->delete();

        return back();
    }

    /**
     * Restore specified Client in storage.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function unblock(Request $request): RedirectResponse
    {
        $idClient = $request->validate(['id_client' => ['bail', 'required', 'integer', 'min:1', 'max:999999999']])['id_client'];
        Client::onlyTrashed()->findOrFail($idClient)->restore();

        return back();
    }
}

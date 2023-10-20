<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Site\Client;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    /**
     * Display a listing of the Clients.
     */
    public function index()
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
        if ($request->has('blockClient')) {
            $clientModel = new Client();

            $idClient = $request->post('id_client');
            $clientModel->deleteClient($idClient);
            if ($idClient == $request->session()->get('id_client')) {
                $request->session()->forget('id_client');
            }
        }

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
        if ($request->has('unblockClient')) {
            $clientModel = new Client();

            $idClient = $request->post('id_client');
            $clientModel->restoreClient($idClient);
        }

        return back();
    }
}
<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Site\Client;
use App\Models\Site\Review;
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
        if ($request->has('blockClient')) {
            $clientModel = new Client();
            $reviewModel = new Review();

            $idClient = $request->validate(['id_client' => ['bail', 'integer', 'min: 1', 'max:9223372036854775807']])['id_client'];
            $reviewModel->deleteClientReviews($idClient);
            $clientModel->deleteClient($idClient);
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
            $reviewModel = new Review();

            $idClient = $request->validate(['id_client' => ['bail', 'integer', 'min: 1', 'max:9223372036854775807']])['id_client'];
            $clientModel->restoreClient($idClient);
            $reviewModel->restoreClientReviews($idClient);
        }

        return back();
    }
}

<?php

namespace App\Http\Controllers\Site;

use App\Models\Site\Client;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class ClientController extends Controller
{
    /**
     * Display a listing of the Clients.
     */
    public function index()
    {
        $clients = Client::all();

        return view('admin.clients.index', compact('clients'));
    }

    /**
     * Show one Client's personal page.
     */
    public function show(Request $request)
    {
        $idClient = $request->session()->get('id_client');

        $client = Client::find($idClient);

        return view('profile.client-show', compact('client'));
    }

    /**
     * Show the form for editing the specified Client.
     *
     * @param int $idClient
     */
    public function edit($idClient)
    {
        $client = Client::find($idClient);

        return view('profile.client-update', compact('client'));
    }

    /**
     * Update the specified Client in storage.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function update(Request $request): RedirectResponse
    {
        $clientModel = new Client();

        $postData = $request->post();
        $setClientData = [
            'name' => $postData['name'],
            'surname' => $postData['surname'],
            'email' => $postData['email'],
            'phone' => $postData['tel'],
            'updated_at' => date('Y-m-d H:i:s'),
        ];

        $idClient = $request->post('id_client');
        $clientModel->updateClient($idClient, $setClientData);
        $setClientPasswordData = [
            'password' => Hash::make($postData['password']),
            'updated_at' => date('Y-m-d H:i:s'),
        ];
        $clientModel->updateClientPassword($idClient, $setClientPasswordData);

        return redirect()->route('client.personal');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function destroy(Request $request): RedirectResponse
    {
        $clientModel = new Client();

        $idClient = $request->post('id_client');
        $clientModel->deleteClient($idClient);

        return redirect()->route('index');
    }
}

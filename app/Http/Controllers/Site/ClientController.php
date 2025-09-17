<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Http\Requests\ClientRequest;
use App\Http\Requests\PasswordRequest;
use App\Models\Client;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ClientController extends Controller
{
    /**
     * Show one Client's personal page.
     *
     * @param Request $request
     * @return View|RedirectResponse
     */
    public function show(Request $request): View|RedirectResponse
    {
        $idClient = $request->session()->get('id_client');
        $client = Client::findOrFail($idClient);

        return view('profile.client-show', compact('client'));
    }

    /**
     * Show the form for editing the specified Client.
     *
     * @param int $idClient
     * @return View|RedirectResponse
     */
    public function edit(int $idClient): View|RedirectResponse
    {
        $client = Client::findOrFail($idClient);

        return view('profile.client-update', compact('client'));
    }

    /**
     * Update the specified Client in storage.
     *
     * @param ClientRequest $request
     * @param Client $client
     * @return RedirectResponse
     */
    public function update(ClientRequest $request, Client $client): RedirectResponse
    {
        $status = false;
        $idClient = $request->session()->get('id_client');
        if ($client->passwordCheck($idClient, $request->validated('password'))) {
            $client->update($request->safe()->except('password'));
            $status = true;
        }

        return $status ? back()->with('status', 'profileUpdated')
                        : back()->withErrors(['password' => trans('site_profile.wrongPassword')])->withInput();
    }

    /**
     * Update the specified Client's Password in storage.
     *
     * @param PasswordRequest $request
     * @param Client $client
     * @return RedirectResponse
     */
    public function updatePass(PasswordRequest $request, Client $client): RedirectResponse
    {
        $idClient = $request->session()->get('id_client');

        if (!$client->passwordCheck($idClient, $request->validated('old_password'))) {
            return back()->withErrors(['old_password' => trans('site_profile.wrongPassword')]);
        }

        if ($request->validated('new_password') !== $request->validated('new_password2')) {
            return back()->withErrors(['new_password' => trans('site_profile.differentPasswords')]);
        }

        $client->updateClientPassword($idClient, [
            'password'   => Hash::make($request->validated('new_password')),
            'updated_at' => now(),
        ]);

        return back()->with('status', 'passwordUpdated');
    }

    /**
     * Soft-Delete specified Client in storage.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function destroy(Request $request): RedirectResponse
    {
        $clientModel = new Client();

        $status = false;
        $password = $request->validate(['passwordForDelete' => ['bail', 'required', 'string', 'min:8', 'max:255']])['passwordForDelete'];
        $idClient = $request->session()->get('id_client');
        if ($clientModel->passwordCheck($idClient, $password)) {
            $clientModel->query()->findOrFail($idClient)->delete();
            $request->session()->forget('id_client');
            $request->session()->forget('cart');
            $status = true;
        }

        return $status ? redirect()->route('index')
                        : back()->withErrors(['passwordForDelete' => trans('site_profile.wrongPassword')]);
    }
}

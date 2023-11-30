<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Http\Requests\ClientRequest;
use App\Http\Requests\PasswordRequest;
use App\Models\Site\Client;
use App\Models\Site\Review;
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
     * @return View
     */
    public function show(Request $request): View
    {
        $idClient = $request->session()->get('id_client');
        $client = Client::findOrFail($idClient);

        return view('profile.client-show', compact('client'));
    }

    /**
     * Show the form for editing the specified Client.
     *
     * @param int $idClient
     * @return View
     */
    public function edit(int $idClient): View
    {
        $client = Client::findOrFail($idClient);

        return view('profile.client-update', compact('client'));
    }

    /**
     * Update the specified Client in storage.
     *
     * @param ClientRequest $request
     * @return RedirectResponse
     */
    public function update(ClientRequest $request): RedirectResponse
    {
        $clientModel = new Client();

        $status = false;
        $idClient = $request->session()->get('id_client');
        if ($clientModel->passwordCheck($idClient, $request->validated('password'))) {
            $client = $clientModel::findOrFail($idClient);
            $client->fill($request->safe()->except('password'));
            if ($client->isDirty()) {
                $client->save();
            }
            $status = true;
        }

        return $status ? back()->with('status', 'profileUpdated')
                        : back()->withErrors(['password' => trans('site_profile.wrongPassword')]);
    }

    /**
     * Update the specified Client's Password in storage.
     *
     * @param PasswordRequest $request
     * @return RedirectResponse
     */
    public function updatePass(PasswordRequest $request): RedirectResponse
    {
        $clientModel = new Client();

        $status = 'wrongPassword';
        $idClient = $request->session()->get('id_client');
        if ($clientModel->passwordCheck($idClient, $request->validated('old_password'))) {
            if ($request->validated('new_password') == $request->validated('new_password2')) {
                $setClientPasswordData = [
                    'password' => Hash::make($request->validated('new_password')),
                    'updated_at' => now(),
                ];
                $clientModel->updateClientPassword($idClient, $setClientPasswordData);
                $status = 'success';
            } else {
                $status = 'differentPasswords';
            }
        }

        return $status == 'success' ? back()->with('status', 'passwordUpdated')
            : ($status == 'wrongPassword' ? back()->withErrors(['old_password' => trans('site_profile.wrongPassword')])
            : back()->withErrors(['new_password' => trans('site_profile.differentPasswords')]));
    }

    /**
     * Soft-Delete specified Client in storage.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function destroy(Request $request): RedirectResponse
    {
        $status = false;
        if ($request->has('deleteClient')) {
            $clientModel = new Client();
            $reviewModel = new Review();

            $password = $request->validate(['passwordForDelete' => ['bail', 'required', 'string', 'min:8', 'max:255']])['passwordForDelete'];
            $idClient = $request->session()->get('id_client');
            if ($clientModel->passwordCheck($idClient, $password)) {
                $reviewModel->deleteClientReviews($idClient);
                $clientModel->deleteClient($idClient);
                $request->session()->forget('id_client');
                $request->session()->forget('cart');
                $status = true;
            }
        }

        return $status ? redirect()->route('index')
                        : back()->withErrors(['passwordForDelete' => trans('site_profile.wrongPassword')]);
    }
}

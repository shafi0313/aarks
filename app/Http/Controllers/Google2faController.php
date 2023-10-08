<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Client;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;
use PragmaRX\Google2FALaravel\Facade as TwoFactor;

class Google2faController extends Controller
{
    public function index($id = null)
    {
        $client = Client::find($id ?? client()->id);
        // Initialise the 2FA class
        $google2fa = app('pragmarx.google2fa');
        // Add the secret key to the registration data
        $secret_key = $google2fa->generateSecretKey();
        if ($client->two_factor_secret) {
            $secret_key = $client->two_factor_secret;
        }

        // Generate the QR image. This is the image the user will scan with their app
        // to set up two factor authentication
        $QR = $google2fa->getQRCodeInline(
            config('app.name'),
            $client->email,
            $secret_key
        );
        if ($id == null) {
            return view('frontend.profile.2fa.index', compact(['client', 'QR', 'secret_key']));
        }
        return view('admin.profile.2fa-client.index', compact(['client', 'QR', 'secret_key']));
    }
    /**
     * Validate & configure two-factor authentication.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function enable(Request $request, Client $client)
    {
        $request->validate([
            'key'  => ['required', 'string'],
            'code' => ['required', 'string', function ($attribute, $value, $fail) use ($request) {
                if (!TwoFactor::verifyKey($request->key, $value)) {
                    $fail('The code you provided is not valid.');
                }
            }],
        ]);

        $client->update([
            'two_factor_secret' => $request->key,
        ]);
        Alert::success('Two-factor authentication On successful');
        return redirect()->back();
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request, Client $client)
    {
        $request->validate([
            'key'  => ['required', 'string'],
            'code' => ['required', 'string', function ($attribute, $value, $fail) use ($request) {
                if (!TwoFactor::verifyKey($request->key, $value)) {
                    $fail('The code you provided is not valid.');
                }
            }],
        ]);

        $client->update([
            'two_factor_secret' => null,
        ]);
        Alert::success('Two-factor authentication OFF successful');
        return redirect()->back();
    }
}

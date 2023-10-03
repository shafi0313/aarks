<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;
use PragmaRX\Google2FALaravel\Facade as TwoFactor;

class Google2faAdminController extends Controller
{
    public function adminUser()
    {
        $admins = Admin::all();
        return view('admin.profile.2fa.admin-user', compact('admins'));
    }

    public function index($id)
    {
        $admin = Admin::find($id);
        // Initialise the 2FA class
        $google2fa = app('pragmarx.google2fa');
        // Add the secret key to the registration data
        $secret_key = $google2fa->generateSecretKey();
        if ($admin->two_factor_secret) {
            $secret_key = $admin->two_factor_secret;
        }

        // Generate the QR image. This is the image the user will scan with their app
        // to set up two factor authentication
        $QR = $google2fa->getQRCodeInline(
            config('app.name'),
            $admin->email,
            $secret_key
        );
        return view('admin.profile.2fa.index', compact(['admin', 'QR', 'secret_key']));
    }
    /**
     * Validate & configure two-factor authentication.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function enable(Request $request, Admin $admin)
    {
        $request->validate([
            'key'  => ['required', 'string'],
            'code' => ['required', 'string', function ($attribute, $value, $fail) use ($request) {
                if (!TwoFactor::verifyKey($request->key, $value)) {
                    $fail('The code you provided is not valid.');
                }
            }],
        ]);

        $admin->update([
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
    public function destroy(Request $request)
    {
        $request->validate([
            'key'  => ['required', 'string'],
            'code' => ['required', 'string', function ($attribute, $value, $fail) use ($request) {
                if (!TwoFactor::verifyKey($request->key, $value)) {
                    $fail('The code you provided is not valid.');
                }
            }],
        ]);

        admin()->update([
            'two_factor_secret' => null,
        ]);
        Alert::success('Two-factor authentication OFF successful');
        return redirect()->back();
    }
}

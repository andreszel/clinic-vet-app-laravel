<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        return view('admin.profile.index', ['user' => $user]);
    }

    public function settings()
    {
        $user = Auth::user();
        $id = Auth::id();

        return view('admin.profile.settings_edit');
    }

    public function settingsUpdate(Request $request)
    {
        $old_password = $request->get('old_password');
        $password = $request->get('password');
        $password_confirmation = $request->get('password_confirmation');

        $req = [
            'password' => $password,
            'password_confirmation' => $password_confirmation
        ];
        Validator::make($req, [
            'password' => [
                'required',
                'string',
                'min:8',
                'regex:/[a-z]/',      // must contain at least one lowercase letter
                'regex:/[A-Z]/',      // must contain at least one uppercase letter
                'regex:/[0-9]/',      // must contain at least one digit
                'regex:/[@$!%*#?&]/', // must contain a special character
                'confirmed'
            ],
            'password_confirmation' => 'required| min:8'
        ])->validate();

        $hashed_random_password = Hash::make($password);
        $id = Auth::id();
        $user = User::where('id', $id)->first();

        // Sprawdzamy, czy podane stare hasło jest takie samo
        if (!Hash::check($old_password, $user->password)) {
            return redirect()->route('me.profile')->with('error', 'Wpisane stare hasło jest nieprawiłowe!');
        }

        $user->password = $hashed_random_password;
        $user->set_pass = true;
        $user->date_set_pass = date("Y-m-d");

        $user->save();

        return redirect()->route('me.profile')->with('success', 'Udało się. Hasło zostało zmienione.');
    }

    public function events()
    {
        $user = Auth::user();
        $id = Auth::id();

        return view('admin.profile.events');
    }
}

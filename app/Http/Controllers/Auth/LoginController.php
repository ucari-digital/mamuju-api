<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Hash;
use App\Helper\Response;

use App\Model\Users;
class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    // public function __construct()
    // {
    //     $this->middleware('guest')->except('logout');
    // }

    public function login(Request $request)
    {
        $user = Users::where('email', $request->email)->first();

        if (!$user) {
            return Response::json('', 'pengguna tidak ditemukan', 'failed', 403);
        }

        if (Hash::check($request->password, $user->password)) {
            return Response::json($user, 'login berhasil', 'success', 200);
        } else {
            return Response::json('', 'pengguna tidak ditemukan', 'failed', 403);
        }
    }
}

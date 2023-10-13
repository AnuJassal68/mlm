<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Hash;
class LoginController extends Controller
{
    //
    use AuthenticatesUsers;

    public function showLoginForm()
    {
        return view('front.login');
    }
    public function __construct()
{
    $this->middleware('guest')->except('logout');
}

/**
 * Authenticate a user based on email and password.
 *
 * @param  \Illuminate\Http\Request  $request
 * @return \Illuminate\Http\RedirectResponse
 */
    public function login(Request $request)
    {
        $emailId = $request->input('emailid');
        $loginPassword = $request->input('loginpassword');
    
        if (!empty($emailId) ) {
           $user = User::where('loginid', $emailId)
                  ->where('bemail', '1')
                  ->where('bActive', 'Y')
                  ->first();
    
            if ($user && Hash::check($loginPassword, $user->loginpassword)) {
                $use = $request->session()->put('user_id', $user->id);
                // print_r($use);exit;
                $request->session()->put('loginid', $user->loginid);
                $request->session()->put('user_name', $user->firstname);
                $request->session()->put('USER.PID', $user->packageid);
                $request->session()->put('USER.SESSID', $user->id . time() . rand(9, 999)); 
                $user->logsessid = $request->session()->get('USER.SESSID');
                $user->save();
                $insert = [
                    'userid' => $user->id,
                    'logintime' => time(),
                    'ipaddress' => $request->ip(),
                ];
                DB::table('tbl_log')->insert($insert);
    
                return redirect()->route('user/dashboard');
            } else {
                $emsg = "Invalid Login-Id or Password!";
                $etype = "danger";
            }
        } else {
            $emsg = "Login Id & Password cannot be left blank!";
            $etype = "danger";
        }

        return back()->with(compact('emsg', 'etype'));
    }
    /**
 * Process the request to retain the password.
 *
 * @param  \Illuminate\Http\Request  $request
 * @return \Illuminate\Http\RedirectResponse
 */

    public function retainPassword(Request $request)
    {
        $emailId = $request->input('emailid');
        if (!empty($emailId)) {
            $user = User::where('loginid', $emailId)->first();

            if ($user) {
                $request->session()->increment('TSES');
                if ($request->session()->get('TSES') <= 3) {
                }
                return redirect()->route('password-sent');
            } else {
                $emsg = "Invalid Login-Id!";
                $etype = "danger";
            }
        } else {
            $emsg = "Login Id cannot be left blank!";
            $etype = "danger";
        }

        return back()->with(compact('emsg', 'etype'));
    }
/**
 * Display the user dashboard.
 *
 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
 */
    public function dashboard()
    {
     
        $userId = session('user_id');
        $uinfo = User::where('id', $userId)->first();
        return view('dashboard', compact('uinfo'));
    }
/**
 * Logout the user and clear session data.
 *
 * @param  \Illuminate\Http\Request  $request
 * @return \Illuminate\Http\RedirectResponse
 */
    public function logout(Request $request)
    {
        $request->session()->forget('USER');
        $request->session()->forget('TSES');

        return redirect('/');
    }

    public function signup()
    {
        return view('front.signup');
    }


    public function showChangePasswordForm()
    {
        return view('user.change-password');
    }
/**
 * Change the user's login password.
 *
 * @param  \Illuminate\Http\Request  $request
 * @return \Illuminate\Http\RedirectResponse
 */
    public function changePassword(Request $request)
    {
        $emsg = null;
        $etype = "danger";

        if ($request->isMethod('post')) {
            $request->validate([
                'cpassword' => 'required|string|min:6', // Change the validation rules as per your requirements
                'npassword' => 'required|string|min:6',
                'rnpassword' => 'required|string|min:6|same:npassword',
            ]);

            $user = User::find(Session::get('user_id'));

            // if (!$user) {
            //     // Handle the case when the user is not found in the database
            // }

            if ($user->loginpassword == $request->input('cpassword')) {
                // Update the user's login password
                $user->loginpassword = $request->input('rnpassword');
                $user->save();

                return redirect()->route('changePasswordForm')->with([
                    'emsg' => 'Login Password has been changed successfully !',
                    'etype' => 'success',
                ]);
            } else {
                return redirect()->route('changePasswordForm')->with([
                    'emsg' => 'Invalid Current Password. Please enter a valid current password to continue!',
                    'etype' => 'danger',
                ]);
            }
        }
    }
}
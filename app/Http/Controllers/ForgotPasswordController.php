<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\PasswordResetMail; // Import your mail class

class ForgotPasswordController extends Controller
{
    /**
 * Send a password reset email to the user.
 *
 * @param  \Illuminate\Http\Request  $request
 * @return \Illuminate\Http\RedirectResponse
 */
    public function sendPasswordResetEmail(Request $request)
    {
        $email = $request->input('emailid');

        $user = User::where('emailid', $email)->where('bemail', 1)->where('bActive', 'Y')->first();
        // dd($user);
        if (!$user) {
            return redirect()->route('forgot-password')->with('error', 'Invalid email or user not found.');
        }

        $newPassword = Str::random(8);
        $user->loginpassword = bcrypt($newPassword);
        $user->save();

        // Send an email with the new password
        Mail::to($email)->send(new PasswordResetMail($user, $newPassword));

        return redirect('log-in')->with('success', 'Password reset successful. Check your email for the new password.');
    }

    public function forgetpasswordpage()
    {

        return view('front.forget-password');
    }
}

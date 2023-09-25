<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\PasswordResetMail; // Import your mail class

class ForgotPasswordController extends Controller
{
    public function sendPasswordResetEmail(Request $request)
    {
      $email = $request->input('emailid');

        // Check if the email exists in your database
         $user = User::where('emailid', $email)->where('bemail', 1)->where('bActive', 'Y')->first();

        if (!$user) {
            return redirect()->route('forgot-password')->with('error', 'Invalid email or user not found.');
        }

        // Generate a new password and send it via email
        $newPassword = Str::random(8); // Generate a new password (you can customize this)
        $user->loginpassword = bcrypt($newPassword);
        $user->save();

        // Send an email with the new password
        Mail::to($email)->send(new PasswordResetMail($user, $newPassword));

        return redirect('log-in')->with('success', 'Password reset successful. Check your email for the new password.');
    }

    public function forgetpasswordpage(){
       
        return view ('front.forget-password');
    }
}


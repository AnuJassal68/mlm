<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\User;
use App\Models\Notification;

class PasswordResetMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $newPassword;
    public $notificationTemplate; // Add this property

    /**
     * Create a new message instance.
     *
     * @param User $user The user receiving the email.
     * @param string $newPassword The new password generated for the user.
     * @param string $action The action (e.g., 'password_reset').
     *
     * @return void
     */
    public function __construct(User $user, $newPassword)
    {
        $this->user = $user;
        $this->newPassword = $newPassword;

        // Retrieve the notification template based on the action
      return  $notification = Notification::where('title', 'Forgot Password')->get(); // Change this line
        $this->notificationTemplate = $notification ? $notification->notification : '';
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $userDetails = [
            'FULLNAME' => $this->user->firstname . ' ' . $this->user->lastname,
            'IP_ADDRESS' => $this->user->ipaddress,
            'DATE_TIME' => now()->format('Y-m-d H:i:s'),
            'LOGINID' => $this->user->loginid,
        ];
 
        return $this->subject('Password Reset')
            ->view('password-reset', [
                'userDetails' => $userDetails,
                'notificationTemplate' => $this->notificationTemplate, // Pass the template to the Blade view
            ]);
    }
}


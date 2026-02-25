<?php 
namespace app\Mail\auth;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class VerifyEmail extends Mailable
{
    use Queueable, SerializesModels;
    
    public $token;
    
    public function __construct($token)
    {
        $this->token = $token;
    }
    
    public function build()
    {
        // Generate the verification URL with a fallback
        $verificationUrl = route('verification.verify', ['token' => $this->token]) ?? 'Invalid URL';

        // Pass the URL to the email view
        return $this->view('emails.verify-email')
                    ->with([
                        'verificationUrl' => $verificationUrl,
                        'token' => $this->token, // Pass token if needed separately
                    ]);


                    

    }
}
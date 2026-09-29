<?php

namespace App\Mail;

use App\Models\Setting;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PasswordResetCodeMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $code,
        public string $userName
    ) {
    }

    public function build()
    {
        $siteName = Setting::get('mail_from_name') ?: Setting::get('site_name', 'Vibra');
        $logoPath = Setting::get('site_logo');
        $logoUrl = $logoPath ? asset($logoPath) : null;

        return $this->subject("Seu código de recuperação de senha - {$siteName}")
            ->view('emails.password-reset-code')
            ->with([
                'code' => $this->code,
                'userName' => $this->userName,
                'siteName' => $siteName,
                'logoUrl' => $logoUrl,
            ]);
    }
}

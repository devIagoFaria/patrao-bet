<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Mail;

class MailConfigService
{
    /**
     * Apply the SMTP settings stored in the database (configured via the
     * admin panel) to Laravel's runtime mail config, so emails are sent
     * without needing to touch the .env file or restart the app.
     *
     * If no SMTP host has been configured yet, this leaves the app's
     * default mail config (usually the "log" driver) untouched.
     */
    public static function apply(): void
    {
        $host = Setting::get('mail_host');
        if (!$host) {
            return;
        }

        $encryption = Setting::get('mail_encryption', 'tls');

        Config::set('mail.default', 'smtp');
        Config::set('mail.mailers.smtp.transport', 'smtp');
        Config::set('mail.mailers.smtp.scheme', $encryption === 'ssl' ? 'smtps' : 'smtp');
        Config::set('mail.mailers.smtp.host', $host);
        Config::set('mail.mailers.smtp.port', Setting::get('mail_port', 587));
        Config::set('mail.mailers.smtp.username', Setting::get('mail_username'));
        Config::set('mail.mailers.smtp.password', Setting::get('mail_password'));
        Config::set('mail.from.address', Setting::get('mail_from_address', 'no-reply@example.com'));
        Config::set('mail.from.name', Setting::get('mail_from_name', 'Vibra'));

        // Force the mailer to be rebuilt with the config we just applied.
        Mail::purge('smtp');
    }
}

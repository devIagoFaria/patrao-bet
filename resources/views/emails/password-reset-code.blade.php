<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <title>Recuperação de senha</title>
</head>
<body style="margin: 0; padding: 0; background-color: #0c0c0e; font-family: Arial, Helvetica, sans-serif;">
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background-color: #0c0c0e; padding: 30px 0;">
        <tr>
            <td align="center">
                <table role="presentation" width="480" cellpadding="0" cellspacing="0" style="background-color: #161619; border-radius: 12px; overflow: hidden; border: 1px solid #2a2a2e;">
                    <tr>
                        <td style="background-color: #0c0c0e; padding: 24px 30px; text-align: center; border-bottom: 3px solid #f8cc1b;">
                            @if($logoUrl)
                                <img src="{{ $logoUrl }}" alt="{{ $siteName }}" style="max-height: 42px; max-width: 220px; display: inline-block;">
                            @else
                                <span style="font-size: 20px; font-weight: 800; color: #f8cc1b;">{{ strtoupper($siteName) }}</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 30px;">
                            <p style="color: #ffffff; font-size: 15px; margin: 0 0 16px 0;">Olá, {{ $userName }}!</p>
                            <p style="color: #b0b0b8; font-size: 14px; line-height: 1.6; margin: 0 0 24px 0;">
                                Recebemos uma solicitação para redefinir a senha da sua conta. Use o código abaixo para continuar. Este código expira em 30 minutos.
                            </p>
                            <div style="text-align: center; margin: 0 0 24px 0;">
                                <span style="display: inline-block; background-color: #0c0c0e; border: 1px solid #f8cc1b; color: #f8cc1b; font-size: 32px; font-weight: 800; letter-spacing: 8px; padding: 16px 24px; border-radius: 8px;">
                                    {{ $code }}
                                </span>
                            </div>
                            <p style="color: #6b6b70; font-size: 12.5px; line-height: 1.6; margin: 0;">
                                Se você não solicitou a redefinição de senha, ignore este e-mail. Sua senha permanecerá inalterada.
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 16px 30px; border-top: 1px solid #2a2a2e; text-align: center;">
                            <span style="color: #6b6b70; font-size: 11.5px;">© {{ date('Y') }} {{ $siteName }}. Todos os direitos reservados.</span>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>

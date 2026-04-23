<!doctype html>
<html lang="tr">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Şifreniz Sıfırlandı</title>
    </head>
    <body style="margin:0;padding:0;background:#f5f6fb;font-family:Arial, Helvetica, sans-serif;">
        <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background:#f5f6fb;padding:24px 12px;">
            <tr>
                <td align="center">
                    <table role="presentation" width="560" cellspacing="0" cellpadding="0" style="background:#ffffff;border-radius:12px;overflow:hidden;box-shadow:0 6px 20px rgba(0,0,0,0.08);">
                        <tr>
                            <td style="background:#242745;color:#fff;padding:18px 22px;">
                                <div style="font-size:16px;font-weight:700;letter-spacing:0.2px;">{{ config('app.name') }}</div>
                                <div style="opacity:0.85;font-size:12px;margin-top:4px;">Şifreniz yönetici tarafından sıfırlandı.</div>
                            </td>
                        </tr>
                        <tr>
                            <td style="padding:22px;">
                                <p style="margin:0 0 12px 0;color:#111827;font-size:14px;line-height:1.5;">
                                    Sisteme giriş yapmak için aşağıdaki şifre ile oturum açın ve yeni şifre belirleyin.
                                </p>

                                <div style="margin:16px 0;padding:16px;border:1px dashed #d1d5db;border-radius:12px;text-align:center;">
                                    <div style="font-size:28px;font-weight:800;letter-spacing:10px;color:#242745;">{{ $newPassword }}</div>
                                </div>

                              
                            </td>
                        </tr>
                        <tr>
                            <td style="padding:14px 22px;background:#f9fafb;color:#6b7280;font-size:11px;">
                                Bu e-postayi siz talep etmediyseniz yok sayabilirsiniz.
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </body>
</html>


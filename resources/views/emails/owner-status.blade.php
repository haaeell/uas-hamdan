<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $headline }}</title>
</head>
<body style="margin:0; padding:0; background:#f8fafc; font-family:Arial, Helvetica, sans-serif; color:#0f172a;">
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="background:#f8fafc; padding:32px 16px;">
        <tr>
            <td align="center">
                <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="max-width:640px; background:#ffffff; border:1px solid #e2e8f0; border-radius:20px; overflow:hidden;">
                    <tr>
                        <td style="background:linear-gradient(135deg, #dc2626, #b91c1c); padding:28px 32px; color:#ffffff;">
                            <div style="font-size:12px; letter-spacing:1px; text-transform:uppercase; font-weight:700; opacity:0.9;">Notifikasi Akun</div>
                            <div style="font-size:28px; line-height:1.2; font-weight:800; margin-top:10px;">{{ $headline }}</div>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:32px;">
                            <p style="margin:0 0 16px; font-size:16px; line-height:1.7;">Halo {{ $owner->name }},</p>
                            <p style="margin:0 0 20px; font-size:16px; line-height:1.7;">{{ $messageLine }}</p>

                            <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="margin:0 0 24px;">
                                <tr>
                                    <td style="background:#f8fafc; border:1px solid #e2e8f0; border-radius:16px; padding:20px;">
                                        <div style="font-size:12px; text-transform:uppercase; letter-spacing:0.8px; color:#64748b; font-weight:700; margin-bottom:10px;">Detail Akun</div>
                                        <div style="font-size:14px; line-height:1.8; color:#0f172a;">
                                            <strong>Email:</strong> {{ $owner->email }}<br>
                                            <strong>Status:</strong> {{ $statusLabel }}
                                        </div>
                                    </td>
                                </tr>
                            </table>

                            <p style="margin:0 0 24px; font-size:15px; line-height:1.7; color:#334155;">
                                Silakan masuk ke sistem untuk melihat pembaruan akun Anda.
                            </p>

                            @if($actionUrl && $actionLabel)
                                <table role="presentation" cellspacing="0" cellpadding="0" border="0">
                                    <tr>
                                        <td style="border-radius:12px; background:#dc2626;">
                                            <a href="{{ $actionUrl }}" style="display:inline-block; padding:14px 22px; color:#ffffff; text-decoration:none; font-weight:700; font-size:15px;">{{ $actionLabel }}</a>
                                        </td>
                                    </tr>
                                </table>
                            @endif

                            <p style="margin:28px 0 0; font-size:13px; line-height:1.6; color:#64748b;">
                                Email ini dikirim otomatis oleh {{ config('app.name') }}.
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>

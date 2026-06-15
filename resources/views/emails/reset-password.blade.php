<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
</head>
<body style="margin:0; padding:0; background:#f8fafc; font-family:Arial, Helvetica, sans-serif; color:#0f172a;">
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="background:#f8fafc; padding:32px 16px;">
        <tr>
            <td align="center">
                <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="max-width:640px; background:#ffffff; border:1px solid #e2e8f0; border-radius:20px; overflow:hidden;">
                    <tr>
                        <td style="background:linear-gradient(135deg, #dc2626, #b91c1c); padding:28px 32px; color:#ffffff;">
                            <div style="font-size:12px; letter-spacing:1px; text-transform:uppercase; font-weight:700; opacity:0.9;">Keamanan Akun</div>
                            <div style="font-size:28px; line-height:1.2; font-weight:800; margin-top:10px;">Reset Password</div>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:32px;">
                            <p style="margin:0 0 16px; font-size:16px; line-height:1.7;">Halo {{ $user->name }},</p>
                            <p style="margin:0 0 20px; font-size:16px; line-height:1.7;">
                                Kami menerima permintaan untuk mereset password akun Anda. Klik tombol di bawah untuk membuat password baru.
                            </p>

                            <div style="margin:0 0 24px; background:#f8fafc; border:1px solid #e2e8f0; border-radius:18px; padding:22px; text-align:center;">
                                <div style="font-size:12px; text-transform:uppercase; letter-spacing:1px; color:#64748b; font-weight:700; margin-bottom:10px;">Link Berlaku Selama</div>
                                <div style="font-size:34px; letter-spacing:4px; font-weight:800; color:#dc2626;">60 Menit</div>
                            </div>

                            <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="margin:0 0 24px;">
                                <tr>
                                    <td style="background:#f8fafc; border:1px solid #e2e8f0; border-radius:16px; padding:20px;">
                                        <div style="font-size:14px; line-height:1.8; color:#0f172a;">
                                            <strong>Email:</strong> {{ $user->email }}<br>
                                            <strong>Waktu Permintaan:</strong> {{ now()->format('d M Y, H:i') }} WIB
                                        </div>
                                    </td>
                                </tr>
                            </table>

                            <table role="presentation" cellspacing="0" cellpadding="0" border="0">
                                <tr>
                                    <td style="border-radius:12px; background:#dc2626;">
                                        <a href="{{ $resetUrl }}" style="display:inline-block; padding:14px 22px; color:#ffffff; text-decoration:none; font-weight:700; font-size:15px;">Buat Password Baru</a>
                                    </td>
                                </tr>
                            </table>

                            <p style="margin:28px 0 0; font-size:13px; line-height:1.6; color:#64748b;">
                                Jika Anda tidak merasa meminta reset password, abaikan email ini. Password Anda tidak akan berubah.
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>

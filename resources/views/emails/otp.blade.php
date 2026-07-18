<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Código de verificación WalkyDog</title>
</head>
<body style="font-family: 'Segoe UI', sans-serif; background-color: #f8fafc; padding: 30px; margin: 0;">
    <table align="center" border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 600px; background-color: #ffffff; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 12px rgba(0,0,0,0.05);">
        <tr>
            <td style="background-color: #d35400; padding: 25px; text-align: center; color: #ffffff;">
                <h1 style="margin: 0; font-size: 24px; font-weight: 800; letter-spacing: -0.5px;">WalkyDog 🐾</h1>
            </td>
        </tr>
        <tr>
            <td style="padding: 30px; color: #1e293b;">
                <p style="font-size: 16px; margin-top: 0;">Hola, <strong>{{ $user->nombres }}</strong>:</p>
                <p style="font-size: 14px; color: #64748b;">Hemos recibido una solicitud de inicio de sesión para tu cuenta de WalkyDog. Usa el siguiente código de un solo uso (OTP) para verificar tu identidad:</p>
                
                <div style="background-color: #fffaf0; border: 2px dashed #d35400; border-radius: 12px; padding: 20px; text-align: center; margin: 25px 0;">
                    <span style="font-family: monospace; font-size: 32px; font-weight: 800; color: #d35400; letter-spacing: 5px;">{{ $otp }}</span>
                </div>

                <p style="font-size: 12px; color: #94a3b8; text-align: center; margin-bottom: 0;">Este código expirará en 5 minutos por motivos de seguridad. Si no solicitaste este código, ignora este correo.</p>
            </td>
        </tr>
        <tr>
            <td style="background-color: #f1f5f9; padding: 15px; text-align: center; font-size: 11px; color: #94a3b8;">
                &copy; 2026 WalkyDog App - Seminario RAD Cotecnova.
            </td>
        </tr>
    </table>
</body>
</html>
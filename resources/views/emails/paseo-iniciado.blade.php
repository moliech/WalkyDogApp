<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Paseo Iniciado</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; margin: 0; padding: 0;">
    <div style="max-width: 600px; margin: 20px auto; padding: 25px; border: 1px solid #e2e8f0; border-radius: 16px; background-color: #ffffff;">
        <h2 style="color: #008080; margin-top: 0;">¡Hola {{ $paseo->mascota->propietario->nombres }}!</h2>
        <p>Queremos avisarte que tu mascota <strong>{{ $paseo->mascota->nombre }}</strong> ha iniciado su paseo programado.</p>
        
        <div style="background-color: #f8fafc; padding: 18px; border-radius: 12px; margin: 20px 0; border: 1px solid #e2e8f0;">
            <p style="margin: 0; font-weight: bold; color: #008080;">Detalles del Paseador:</p>
            <p style="margin: 6px 0 0 0;">Nombre: {{ $paseo->paseador->nombres }} {{ $paseo->paseador->apellidos }}</p>
            <p style="margin: 4px 0 0 0;">Teléfono: {{ $paseo->paseador->telefono ?? 'No registrado' }}</p>
        </div>

        <p>Puedes monitorear el recorrido en tiempo real haciendo clic en el siguiente botón:</p>
        <p style="text-align: center; margin: 25px 0;">
            <a href="{{ route('paseos.monitoreo') }}" style="background-color: #008080; color: white; padding: 12px 24px; text-decoration: none; border-radius: 8px; font-weight: bold; display: inline-block;">Ver Monitoreo en Vivo</a>
        </p>
        <hr style="border: 0; border-top: 1px solid #e2e8f0; margin: 25px 0;">
        <p style="font-size: 11px; color: #94a3b8; text-align: center; margin: 0;">WalkyDog - Cuidado profesional de mascotas.</p>
    </div>
</body>
</html>

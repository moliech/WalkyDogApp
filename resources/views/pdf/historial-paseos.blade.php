<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Reporte de Paseos - WalkyDog</title>
    <style>
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 11px; color: #333; margin: 0; padding: 0; }
        .header { text-align: center; border-bottom: 2px solid #008080; padding-bottom: 12px; margin-bottom: 20px; }
        .logo { font-size: 20px; font-weight: bold; color: #008080; }
        .title { font-size: 14px; margin-top: 5px; color: #555; text-transform: uppercase; }
        .meta { text-align: right; margin-bottom: 15px; font-size: 10px; color: #666; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th { background-color: #008080; color: white; padding: 8px; font-size: 10px; text-transform: uppercase; text-align: left; }
        td { border-bottom: 1px solid #eee; padding: 8px; }
        .badge { display: inline-block; padding: 3px 6px; border-radius: 4px; font-size: 9px; font-weight: bold; }
        .badge-pendiente { background-color: #ffeeb3; color: #856404; }
        .badge-progreso { background-color: #cce5ff; color: #004085; }
        .badge-completado { background-color: #d4edda; color: #155724; }
        .badge-cancelado { background-color: #f8d7da; color: #721c24; }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo">WalkyDog</div>
        <div class="title">Reporte General de Historial de Paseos</div>
    </div>
    <div class="meta">Generado el: {{ now()->format('d/m/Y h:i A') }}</div>

    <table>
        <thead>
            <tr>
                <th>Fecha y Hora</th>
                <th>Mascota</th>
                <th>Paseador</th>
                <th>Monto (COP)</th>
                <th>Estado</th>
            </tr>
        </thead>
        <tbody>
            @forelse($paseos as $p)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($p->created_at)->format('d/m/Y h:i A') }}</td>
                    <td>{{ $p->mascota->nombre }}</td>
                    <td>{{ $p->paseador->nombres }} {{ $p->paseador->apellidos }}</td>
                    <td>${{ number_format($p->pago->monto ?? 0, 0, ',', '.') }}</td>
                    <td>
                        <span class="badge {{ $p->estado == 'en_progreso' ? 'badge-progreso' : ($p->estado == 'completado' ? 'badge-completado' : ($p->estado == 'cancelado' ? 'badge-cancelado' : 'badge-pendiente')) }}">
                            {{ strtoupper($p->estado) }}
                        </span>
                    </td>
                </tr>
            @empty
                <tr><td colspan="5" style="text-align:center;">No hay paseos registrados.</td></tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>

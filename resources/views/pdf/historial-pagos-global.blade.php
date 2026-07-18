<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Reporte Global de Auditoría de Pagos - WalkyDog</title>
    <style>
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 10px; color: #333; margin: 0; padding: 0; }
        .header { text-align: center; border-bottom: 2px solid #008080; padding-bottom: 12px; margin-bottom: 15px; }
        .logo { font-size: 20px; font-weight: bold; color: #008080; }
        .title { font-size: 13px; margin-top: 5px; color: #555; text-transform: uppercase; font-weight: bold; }
        .meta { text-align: right; margin-bottom: 15px; font-size: 9px; color: #666; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th { background-color: #008080; color: white; padding: 7px 10px; font-size: 9px; text-transform: uppercase; text-align: left; }
        td { border-bottom: 1px solid #eee; padding: 7px 10px; vertical-align: top; }
        .badge { display: inline-block; padding: 2px 5px; border-radius: 3px; font-size: 8px; font-weight: bold; text-transform: uppercase; }
        .badge-approved { background-color: #d4edda; color: #155724; }
        .badge-pending { background-color: #ffeeb3; color: #856404; }
        .badge-rejected { background-color: #f8d7da; color: #721c24; }
        .bold { font-weight: bold; color: #111; }
        .details { font-size: 8px; color: #777; margin-top: 2px; }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo">WalkyDog</div>
        <div class="title">Reporte Global de Auditoría de Pagos</div>
    </div>
    <div class="meta">Fecha de Emisión: {{ now()->format('d/m/Y h:i A') }}</div>

    <table>
        <thead>
            <tr>
                <th style="width: 15%;">Fecha</th>
                <th style="width: 10%;">Ref.</th>
                <th style="width: 25%;">Cliente y Mascota</th>
                <th style="width: 25%;">Paseador</th>
                <th style="width: 13%;">Estado Pago</th>
                <th style="width: 12%; text-align: right;">Monto (COP)</th>
            </tr>
        </thead>
        <tbody>
            @php $totalMonto = 0; @endphp
            @forelse($paseos as $p)
                @php $totalMonto += ($p->pago->monto ?? 0); @endphp
                <tr>
                    <td>{{ \Carbon\Carbon::parse($p->pago->created_at)->format('d/m/Y h:i A') }}</td>
                    <td class="bold">#{{ $p->id }}</td>
                    <td>
                        <span class="bold">{{ $p->mascota->propietario->nombres }} {{ $p->mascota->propietario->apellidos }}</span>
                        <div class="details">Mascota: {{ $p->mascota->nombre }} ({{ $p->mascota->tamano }})</div>
                    </td>
                    <td>
                        <span class="bold">{{ $p->paseador->nombres }} {{ $p->paseador->apellidos }}</span>
                        @if($p->paseador->perfilPaseador)
                            <div class="details">
                                Calif: {{ number_format($p->paseador->perfilPaseador->calificacion_promedio, 2) }} ★
                                @if($p->paseador->perfilPaseador->porcentaje_recargo > 0)
                                    | Recargo: +{{ $p->paseador->perfilPaseador->porcentaje_recargo }}%
                                @endif
                            </div>
                        @endif
                    </td>
                    <td>
                        @if($p->pago->estado_pago == 'approved')
                            <span class="badge badge-approved">Aprobado</span>
                        @elseif($p->pago->estado_pago == 'pending')
                            <span class="badge badge-pending">Pendiente</span>
                        @else
                            <span class="badge badge-rejected">Rechazado</span>
                        @endif
                    </td>
                    <td style="text-align: right;" class="bold">${{ number_format($p->pago->monto ?? 0, 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr><td colspan="6" style="text-align:center;">No se encontraron registros de transacciones para los filtros seleccionados.</td></tr>
            @endforelse
        </tbody>
    </table>

    @if(count($paseos) > 0)
        <table style="margin-top: 15px; border-top: 2px solid #008080;">
            <tbody>
                <tr>
                    <td style="border: 0; width: 60%; font-weight: bold; font-size: 11px;">Total Transado Consolidado:</td>
                    <td style="border: 0; width: 40%; text-align: right; font-weight: bold; font-size: 11px; color: #008080;">
                        ${{ number_format($totalMonto, 0, ',', '.') }} COP
                    </td>
                </tr>
            </tbody>
        </table>
    @endif
</body>
</html>

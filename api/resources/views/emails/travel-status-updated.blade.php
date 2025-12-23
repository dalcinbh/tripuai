<!DOCTYPE html>
<html>

<head>
    <style>
        body {
            font-family: 'Inter', sans-serif;
            color: #1e293b;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
        }

        .header {
            border-bottom: 2px solid #6366f1;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        .status-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 9999px;
            font-weight: bold;
            font-size: 12px;
            text-transform: uppercase;
        }

        .status-aprovado {
            background-color: #ecfdf5;
            color: #059669;
        }

        .status-cancelado {
            background-color: #fef2f2;
            color: #dc2626;
        }

        .details {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .details td {
            padding: 10px;
            border-bottom: 1px solid #f1f5f9;
        }

        .label {
            font-weight: bold;
            color: #64748b;
            font-size: 12px;
            text-transform: uppercase;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h2>TripUAI - Atualização de Viagem</h2>
        </div>

        <p>Olá, <strong>{{ $travelRequest->user->name }}</strong>,</p>
        <p>Houve uma atualização no status do seu pedido de viagem:</p>

        <div class="status-badge status-{{ $travelRequest->status }}">
            {{ $travelRequest->status }}
        </div>

        <table class="details">
            <tr>
                <td class="label">ID do Pedido</td>
                <td>#{{ $travelRequest->id }}</td>
            </tr>
            <tr>
                <td class="label">Solicitante</td>
                <td>{{ $travelRequest->requester_name }}</td>
            </tr>
            <tr>
                <td class="label">Destino</td>
                <td>{{ $travelRequest->destination }}</td>
            </tr>
            <tr>
                <td class="label">Período</td>
                <td>{{ $travelRequest->departure_date->format('d/m/Y') }} até {{ $travelRequest->return_date->format('d/m/Y') }}</td>
            </tr>
        </table>

        <p style="margin-top: 30px; font-size: 12px; color: #94a3b8;">
            Este é um e-mail automático enviado pelo sistema de gestão TripUAI.
        </p>
    </div>
</body>

</html>
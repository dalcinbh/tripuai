<!DOCTYPE html>
<html>

<head>
    <style>
        body {
            font-family: 'Inter', sans-serif;
            color: #1e293b;
            line-height: 1.5;
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

        .info-box {
            background-color: #f8fafc;
            padding: 15px;
            border-radius: 6px;
            border-left: 4px solid #6366f1;
            margin: 20px 0;
        }

        .details {
            width: 100%;
            border-collapse: collapse;
        }

        .details td {
            padding: 8px 0;
            border-bottom: 1px solid #f1f5f9;
        }

        .label {
            font-weight: bold;
            color: #64748b;
            font-size: 11px;
            text-transform: uppercase;
            width: 120px;
        }

        .footer {
            margin-top: 30px;
            font-size: 12px;
            color: #94a3b8;
            text-align: center;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h2 style="color: #6366f1; margin: 0;">TripUAI</h2>
        </div>

        <p>Olá, <strong>{{ $travelRequest->user->name }}</strong>,</p>
        <p>Sua solicitação de viagem foi registrada com sucesso e agora aguarda análise da administração.</p>

        <div class="info-box">
            <h4 style="margin: 0 0 10px 0;">Resumo da Solicitação</h4>
            <table class="details">
                <tr>
                    <td class="label">Protocolo</td>
                    <td>#{{ $travelRequest->id }}</td>
                </tr>
                <tr>
                    <td class="label">Viajante</td>
                    <td>{{ $travelRequest->requester_name }}</td>
                </tr>
                <tr>
                    <td class="label">Destino</td>
                    <td>{{ $travelRequest->destination }}</td>
                </tr>
                <tr>
                    <td class="label">Saída</td>
                    <td>{{ $travelRequest->departure_date->format('d/m/Y') }}</td>
                </tr>
                <tr>
                    <td class="label">Retorno</td>
                    <td>{{ $travelRequest->return_date->format('d/m/Y') }}</td>
                </tr>
            </table>
        </div>

        <p>Você receberá uma nova notificação assim que houver uma alteração no status do seu pedido.</p>

        <div class="footer">
            TripUAI — Sistema de Gestão de Viagens Corporativas
        </div>
    </div>
</body>

</html>
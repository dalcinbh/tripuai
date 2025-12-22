<h1>Olá, {{ $travelRequest->requester_name }}!</h1>
<p>Houve uma atualização no seu pedido de viagem.</p>
<p><strong>Destino:</strong> {{ $travelRequest->destination }}</p>
<p><strong>Novo Status:</strong> {{ ucfirst($travelRequest->status) }}</p>

@if($travelRequest->status === 'aprovado')
<p>Prepare as malas! Sua viagem foi confirmada.</p>
@else
<p>Infelizmente seu pedido não pôde ser atendido neste momento.</p>
@endif

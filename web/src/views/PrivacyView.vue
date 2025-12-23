<script setup lang="ts">
import PublicNavbar from '@/components/PublicNavbar.vue';
</script>

<template>
  <div>
    <PublicNavbar />
    <div class="min-h-screen bg-neutral-50 py-12 px-4 sm:px-6 lg:px-8 font-sans">
      <div class="max-w-3xl mx-auto bg-white p-8 rounded-2xl shadow-sm border border-neutral-200">
        <h1 class="text-3xl font-extrabold text-neutral-900 mb-6">Política de Privacidade</h1>
        
        <div class="prose prose-neutral max-w-none text-neutral-600 space-y-8">
          <p class="text-lg leading-relaxed">
            No <strong>TripUAI</strong>, a privacidade e a segurança dos seus dados são tratadas com a mesma hospitalidade e seriedade que dedicamos às nossas viagens. Esta política descreve como coletamos e protegemos suas informações em conformidade com a <strong>Lei Geral de Proteção de Dados (LGPD)</strong>.
          </p>

          <div>
            <h2 class="text-xl font-bold text-neutral-900 mb-3">1. Compromisso com a Diversidade e Inclusão</h2>
            <p class="leading-relaxed">
              O TripUAI é um ambiente que celebra a pluralidade. Coletamos dados de perfil com o único objetivo de facilitar a logística de viagens e garantir que necessidades específicas de acessibilidade ou segurança de grupos <strong>LGBTQIAPN+</strong>, étnico-raciais ou de PCDs sejam respeitadas durante o deslocamento corporativo. Não toleramos qualquer forma de discriminação e garantimos que esses dados sensíveis sejam protegidos por criptografia de ponta a ponta.
            </p>
          </div>

          <div>
            <h2 class="text-xl font-bold text-neutral-900 mb-3">2. Coleta de Dados</h2>
            <p class="mb-2">Coletamos informações essenciais para a operação:</p>
            <ul class="list-disc pl-5 space-y-1">
              <li><strong>Dados de Identificação:</strong> Nome, e-mail e perfil administrativo.</li>
              <li><strong>Dados de Viagem:</strong> Destinos, datas de ida/volta e histórico de solicitações.</li>
              <li><strong>Finalidade:</strong> Os dados são utilizados exclusivamente para o fluxo de aprovação de pedidos e envio de notificações automáticas por e-mail.</li>
            </ul>
          </div>

          <div>
            <h2 class="text-xl font-bold text-neutral-900 mb-3">3. Seus Direitos</h2>
            <p class="leading-relaxed">
              Você possui o direito de acessar, corrigir ou solicitar a exclusão de seus dados a qualquer momento. Nenhuma informação é compartilhada com terceiros sem consentimento prévio, exceto quando necessário para a prestação do serviço de transporte ou hospedagem solicitado.
            </p>
          </div>

          <div>
            <h2 class="text-xl font-bold text-neutral-900 mb-3">4. Segurança</h2>
            <p class="leading-relaxed">
              Utilizamos tecnologias de ponta, incluindo autenticação <strong>JWT (JSON Web Tokens)</strong>, para garantir que apenas usuários autorizados acessem suas informações.
            </p>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

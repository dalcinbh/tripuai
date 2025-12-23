<script setup lang="ts">
import PublicNavbar from '@/components/PublicNavbar.vue';
</script>

<template>
  <div>
    <PublicNavbar />
    <div class="min-h-screen bg-neutral-50 py-12 px-4 sm:px-6 lg:px-8 font-sans">
      <div class="max-w-3xl mx-auto bg-white p-8 rounded-2xl shadow-sm border border-neutral-200">
        <h1 class="text-3xl font-extrabold text-neutral-900 mb-6">Sobre o TripUAI</h1>
        <p class="text-lg text-neutral-600 leading-relaxed space-y-4">
          <span class="block mb-4">
            O <strong>TripUAI</strong> nasceu da união entre a hospitalidade mineira e a vanguarda tecnológica. Nosso nome carrega nossa essência: o "Trip" simboliza a jornada, representada pelo triângulo da bandeira de Minas Gerais em nossa logo, um símbolo de liberdade e equilíbrio.
          </span>
          <span class="block">
            Já o "UAI", além de ser a expressão mais autêntica do nosso povo, termina com <strong>AI (Artificial Intelligence)</strong>, refletindo nosso compromisso com processos inteligentes e automatizados na gestão de viagens corporativas.
          </span>
        </p>
        
        <div class="mt-8 p-6 bg-primary-50 rounded-xl border border-primary-100">
          <h3 class="text-primary-800 font-bold mb-2 text-lg">Nossa Missão</h3>
          <p class="text-primary-700 leading-relaxed">
            Transformar o deslocamento a trabalho em uma experiência fluida, segura e transparente, conectando empresas e colaboradores através de uma plataforma que valoriza a eficiência sem perder o toque humano.
          </p>
        </div>
      </div>
    </div>
  </div>
</template>

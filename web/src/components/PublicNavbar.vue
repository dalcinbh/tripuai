<template>
  <nav class="bg-white shadow-sm border-b border-neutral-200 sticky top-0 z-10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
      <!-- Logo com Link para Home/Login -->
      <router-link to="/" class="flex items-center gap-2 hover:opacity-80 transition-opacity">
        <img src="/favicon.ico" alt="TripUAI" class="h-8 w-auto" />
        <span class="text-xl font-bold text-neutral-900 tracking-tight">TripUAI</span>
      </router-link>
      
      <!-- Ações (Logado vs Não Logado) -->
      <div class="flex items-center gap-4">
        <template v-if="auth.isAuthenticated">
          <span class="hidden sm:block text-sm text-neutral-600">
            Olá, <span class="font-bold text-neutral-900">{{ auth.user?.name }}</span>
          </span>
          <router-link 
            to="/dashboard" 
            class="bg-primary-600 text-white px-4 py-2 rounded-lg text-sm font-bold hover:bg-primary-700 transition-colors shadow-sm shadow-primary-200"
          >
            Ir para Dashboard
          </router-link>
        </template>
        
        <template v-else>
          <router-link 
            to="/login" 
            class="text-neutral-600 hover:text-primary-600 font-bold text-sm transition-colors py-2"
          >
            Entrar
          </router-link>
        </template>
      </div>
    </div>
  </nav>
</template>

<script setup lang="ts">
import { useAuthStore } from '@/stores/auth';

const auth = useAuthStore();
</script>

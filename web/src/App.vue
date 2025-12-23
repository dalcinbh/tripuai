<script setup lang="ts">
import { onMounted } from 'vue';
import { useAuthStore } from '@/stores/auth';

const auth = useAuthStore();

onMounted(() => {
  // Se temos um token mas não temos o user, buscamos os dados
  if (auth.token && !auth.user) {
    auth.getUser();
  }
});

import AppFooter from '@/components/AppFooter.vue';
</script>

<template>
  <div class="min-h-screen flex flex-col font-sans bg-neutral-50 text-neutral-900">
    <div class="flex-grow w-full">
      <router-view />
    </div>
    <AppFooter />
  </div>
</template>
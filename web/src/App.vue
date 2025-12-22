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

</script>

<template>
  <router-view />
</template>
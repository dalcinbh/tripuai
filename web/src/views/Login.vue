<template>
  <div class="min-h-screen bg-neutral-100 flex items-center justify-center p-4">
    <div class="max-w-md w-full bg-white rounded-xl shadow-lg p-8">
      <div class="flex flex-col items-center mb-8">
        <img src="@/assets/logo-tripuai.png" alt="TripUAI" class="w-48 mb-4" />
        <h2 class="text-2xl font-bold text-neutral-900">Aceder ao Painel</h2>
      </div>

      <form @submit.prevent="handleLogin" class="space-y-6">
        <div>
          <label class="block text-sm font-medium text-neutral-700">Email</label>
          <input v-model="email" type="email" required 
            class="mt-1 block w-full px-3 py-2 border border-neutral-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500" />
        </div>

        <div>
          <label class="block text-sm font-medium text-neutral-700">Palavra-passe</label>
          <input v-model="password" type="password" required 
            class="mt-1 block w-full px-3 py-2 border border-neutral-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500" />
        </div>

        <button type="submit" :disabled="loading"
          class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 disabled:opacity-50">
          {{ loading ? 'A entrar...' : 'Entrar' }}
        </button>
      </form>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref } from 'vue';
import { useAuthStore } from '@/stores/auth';
import { useRouter } from 'vue-router';
import { toast } from 'vue3-toastify';

const email = ref('admin@tripuai.com.br');
const password = ref('password');
const loading = ref(false);
const auth = useAuthStore();
const router = useRouter();

const handleLogin = async () => {
  loading.value = true;
  try {
    await auth.login({ email: email.value, password: password.value });
    router.push('/dashboard');
  } catch (error) {
    toast.error('Credenciais inválidas ou erro de conexão');
  } finally {
    loading.value = false;
  }
};
</script>
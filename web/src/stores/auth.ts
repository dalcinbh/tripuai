import { defineStore } from 'pinia';
import { ref, computed } from 'vue';
import api from '../services/api'; // O serviço axios que configuramos

export const useAuthStore = defineStore('auth', () => {
  const user = ref<any>(null);
  const token = ref(localStorage.getItem('token') || '');

  const isAuthenticated = computed(() => !!token.value);
  const isAdmin = computed(() => user.value?.is_admin === 1 || user.value?.is_admin === true); // Valida regra do backend

  async function login(credentials: any) {
    const response = await api.post('/auth/login', credentials);
    token.value = response.data.access_token;
    user.value = response.data.user;
    
    localStorage.setItem('token', token.value);
  }

  function logout() {
    user.value = null;
    token.value = '';
    localStorage.removeItem('token');
  }

  return { user, token, isAuthenticated, isAdmin, login, logout };
});
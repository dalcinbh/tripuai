import { defineStore } from 'pinia';
import { ref, computed } from 'vue';
import api from '../services/api';

export const useAuthStore = defineStore('auth', () => {
  // 1. Tenta recuperar o user já salvo para evitar o flash de "usuário comum"
  const savedUser = localStorage.getItem('user');
  
  const user = ref<any>(savedUser && savedUser !== 'undefined' ? JSON.parse(savedUser) : null);
  const token = ref(localStorage.getItem('token') || '');
  const isInitialLoading = ref(true);

  const isAuthenticated = computed(() => !!token.value);
  
  // 2. Garante que a verificação de Admin funcione com número ou booleano
  const isAdmin = computed(() => {
    return user.value?.is_admin === 1 || user.value?.is_admin === true;
  });

  async function login(credentials: any) {
    const response = await api.post('/auth/login', credentials);
    token.value = response.data.access_token;
    user.value = response.data.user;
    
    // 3. Persistência imediata de ambos no LocalStorage
    localStorage.setItem('token', token.value);
    localStorage.setItem('user', JSON.stringify(user.value));
  }

  async function getUser() {
    if (!token.value) {
      isInitialLoading.value = false; // Se não tem token, para de carregar
      return;
    }
    
    try {
      const response = await api.get('/auth/me'); // Verifique se o path está correto (/auth/me ou /me)
      user.value = response.data;
      localStorage.setItem('user', JSON.stringify(user.value));
    } catch (error) {
      // Se der erro (token expirado), limpa e vai para o login
      logout();
    } finally {
      isInitialLoading.value = false; // SEMPRE desliga o loading aqui
    }
  }

  function logout() {
    user.value = null;
    token.value = '';
    localStorage.removeItem('token');
    localStorage.removeItem('user'); // Limpa o cache para evitar conflito no próximo login
  }

  return { 
    user, 
    token, 
    isAuthenticated, 
    isAdmin, 
    isInitialLoading, 
    login, 
    logout, 
    getUser 
  };
});
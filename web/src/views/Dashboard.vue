<template>
  <div v-if="auth.isInitialLoading && !auth.user" class="min-h-screen flex items-center justify-center">
    <p class="text-neutral-500 font-medium">Restaurando sessão...</p>
  </div>
  <div v-else class="min-h-screen bg-neutral-50">
    <div class="min-h-screen bg-neutral-50">
      <nav class="bg-white shadow-sm border-b border-neutral-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
          <img src="@/assets/logo-tripuai.png" alt="TripUAI" class="h-8 w-auto" />
          <div class="flex items-center gap-4">
            <div v-if="auth.user" class="flex items-center gap-4">
            <span v-if="auth.isAdmin" class="bg-primary-100 text-primary-700 text-xs font-bold px-2 py-1 rounded-full">
              ADMINISTRADOR
            </span>
            <span class="text-neutral-600 text-sm">{{ auth.user?.name }}</span>
            </div>
            <button @click="handleLogout" class="text-secondary-600 hover:text-secondary-700 text-sm font-medium">Sair</button>
          </div>
        </div>
      </nav>

      <main class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        <div class="mb-8 flex justify-between items-center">
          <h1 class="text-2xl font-bold text-neutral-900">Solicitações de Viagem</h1>
          <button v-if="!auth.isAdmin" class="bg-primary-600 text-white px-4 py-2 rounded-lg font-medium hover:bg-primary-700 transition">
            Nova Solicitação
          </button>
          <RequestModal 
            :is-open="isModalOpen" 
            @close="isModalOpen = false" 
            @success="handleSuccess" 
          />
        </div>

        <div class="bg-white shadow rounded-xl overflow-hidden border border-neutral-200">
          <table class="min-w-full divide-y divide-neutral-200">
            <thead class="bg-neutral-50">
              <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase">Solicitante</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase">Destino</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase">Período</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase">Status</th>
                <th v-if="auth.isAdmin" class="px-6 py-3 text-right text-xs font-medium text-neutral-500 uppercase">Ações</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-neutral-200">
              <tr v-for="request in requests" :key="request.id" class="hover:bg-neutral-50 transition">
                <td class="px-6 py-4 text-sm font-medium text-neutral-900">{{ request.user?.name }}</td>
                <td class="px-6 py-4 text-sm text-neutral-600">{{ request.destination }}</td>
                <td class="px-6 py-4 text-sm text-neutral-600">{{ formatDate(request.start_date) }} - {{ formatDate(request.end_date) }}</td>
                <td class="px-6 py-4">
                  <span :class="statusClass(request.status)" class="px-2 py-1 text-xs font-bold rounded-full">
                    {{ request.status.toUpperCase() }}
                  </span>
                </td>
                <td v-if="auth.isAdmin" class="px-6 py-4 text-right space-x-2">
                  <button v-if="request.status === 'pending'" @click="approve(request.id)" class="text-success-500 hover:underline text-sm font-bold">Aprovar</button>
                  <button v-if="request.status === 'pending'" @click="cancel(request.id)" class="text-secondary-500 hover:underline text-sm font-bold">Recusar</button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </main>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue';
import { useAuthStore } from '@/stores/auth';
import { travelService } from '@/services/travelService';
import { useRouter } from 'vue-router';
import { toast } from 'vue3-toastify';
import RequestModal from '@/components/RequestModal.vue';

const router = useRouter();
const isModalOpen = ref(false);

interface TravelRequest {
  id: number;
  destination: string;
  start_date: string;
  end_date: string;
  status: 'pending' | 'approved' | 'canceled';
  user?: {
    name: string;
  };
}

const auth = useAuthStore();
// 2. Tipar o ref como um array de TravelRequest
const requests = ref<TravelRequest[]>([]);
const loading = ref(true);

const handleSuccess = () => {
  isModalOpen.value = false;
  loadRequests();
};

const loadRequests = async () => {
  try {
    loading.value = true;
    requests.value = await travelService.getAll();
  } catch (error) {
    toast.error('Erro ao carregar pedidos.');
  } finally {
    loading.value = false;
  }
};

onMounted(async () => {
  // Chama o getUser para validar a sessão e desligar o "Restaurando sessão..."
  await auth.getUser(); 
  loadRequests();
});

const approve = async (id: number) => {
  await travelService.approve(id);
  toast.success('Viagem aprovada!');
  loadRequests();
};

const cancel = async (id: number) => {
  await travelService.cancel(id);
  toast.success('Viagem cancelada!');
  loadRequests();
};

const handleLogout = () => {
  auth.logout();
  router.push('/login');
};

const formatDate = (date: string) => new Date(date).toLocaleDateString('pt-BR');

const statusClass = (status: string) => {
  if (status === 'approved') return 'bg-success-100 text-success-700';
  if (status === 'canceled') return 'bg-secondary-100 text-secondary-700';
  return 'bg-warning-100 text-warning-700';
};
</script>
<template>
  <div v-if="auth.isInitialLoading && !auth.user" class="min-h-screen flex flex-col items-center justify-center bg-neutral-50">
    <img src="/favicon.ico" class="w-12 h-12 animate-spin mb-4" alt="Carregando..." />
    <p class="text-neutral-500 font-medium animate-pulse">Restaurando sessão...</p>
  </div>

  <div v-else class="min-h-screen bg-neutral-50 font-sans">
    <nav class="bg-white shadow-sm border-b border-neutral-200 sticky top-0 z-10">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
        <img src="@/assets/logo-tripuai.png" alt="TripUAI" class="h-8 w-auto" />
        
        <div class="flex items-center gap-6">
          <div v-if="auth.user" class="flex items-center gap-3">
            <span v-if="auth.isAdmin" class="bg-primary-50 text-primary-700 text-[10px] tracking-wider font-bold px-2.5 py-0.5 rounded-full border border-primary-100">
              ADMINISTRADOR
            </span>
            <div class="flex flex-col items-end">
              <span class="text-neutral-900 text-sm font-semibold leading-none">{{ auth.user?.name }}</span>
              <span class="text-neutral-500 text-[11px]">{{ auth.user?.email }}</span>
            </div>
          </div>
          <button @click="handleLogout" class="text-secondary-600 hover:text-secondary-700 text-sm font-bold transition-colors">
            Sair
          </button>
        </div>
      </div>
    </nav>

    <main class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
      <div class="mb-8 flex justify-between items-end">
        <div>
          <h1 class="text-3xl font-extrabold text-neutral-900 tracking-tight">Solicitações de Viagem</h1>
          <p class="text-neutral-500 mt-1">Gerencie e acompanhe seus pedidos de deslocamento corporativo.</p>
        </div>
        
        <div class="flex items-center gap-4">
          <div v-if="loading" class="flex items-center gap-2 mr-2">
            <img src="/favicon.ico" class="w-5 h-5 animate-spin" />
            <span class="text-xs text-neutral-400 font-medium italic">Sincronizando...</span>
          </div>

          <button 
            @click="isModalOpen = true"
            v-if="!auth.isAdmin" 
            class="bg-primary-600 text-white px-5 py-2.5 rounded-lg font-bold hover:bg-primary-700 active:transform active:scale-95 transition-all shadow-sm shadow-primary-200 flex items-center gap-2"
          >
            <span>+</span> Nova Solicitação
          </button>
        </div>
      </div>

      <div class="bg-white shadow-xl shadow-neutral-200/50 rounded-xl overflow-hidden border border-neutral-200">
        <table class="min-w-full divide-y divide-neutral-200">
          <thead class="bg-neutral-50">
            <tr>
              <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-neutral-500 uppercase tracking-widest">Solicitante</th>
              <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-neutral-500 uppercase tracking-widest">Destino</th>
              <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-neutral-500 uppercase tracking-widest">Período</th>
              <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-neutral-500 uppercase tracking-widest">Status</th>
              <th scope="col" v-if="auth.isAdmin" class="px-6 py-4 text-right text-xs font-bold text-neutral-500 uppercase tracking-widest">Ações</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-neutral-200 bg-white">
            <tr v-for="request in requests" :key="request.id" class="hover:bg-neutral-50/80 transition-colors group">
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm font-bold text-neutral-900">{{ request.requester_name || '---' }}</div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm text-neutral-600 flex items-center gap-1">
                  <span class="text-neutral-400">📍</span> {{ request.destination }}
                </div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm text-neutral-500 font-medium">
                  {{ formatDate(request.departure_date) }} <span class="text-neutral-300 mx-1">→</span> {{ formatDate(request.return_date) }}
                </div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <span :class="statusClass(request.status)" class="px-2.5 py-1 text-[10px] font-black rounded-md uppercase tracking-tighter">
                  {{ request.status === 'solicitado' ? 'Solicitado' : (request.status === 'aprovado' ? 'Aprovado' : 'Cancelado') }}
                </span>
              </td>
              <td v-if="auth.isAdmin" class="px-6 py-4 text-right whitespace-nowrap text-sm font-medium">
                <div v-if="request.status === 'solicitado'" class="flex justify-end gap-3">
                  <button @click="approve(request.id)" class="text-success-600 hover:text-success-700 transition-colors font-bold flex items-center gap-1">
                    ✓ Aprovar
                  </button>
                  <button @click="cancel(request.id)" class="text-secondary-600 hover:text-secondary-700 transition-colors font-bold flex items-center gap-1">
                    ✕ Recusar
                  </button>
                </div>
                <span v-else class="text-neutral-400 italic text-xs">Concluído</span>
              </td>
            </tr>
            <tr v-if="requests.length === 0 && !loading">
              <td colspan="5" class="px-6 py-12 text-center text-neutral-400 italic">
                Nenhuma solicitação encontrada.
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </main>

    <RequestModal 
      :is-open="isModalOpen" 
      @close="isModalOpen = false" 
      @success="handleSuccess" 
    />
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue';
import { useAuthStore } from '@/stores/auth';
import { travelService } from '@/services/travelService';
import { useRouter } from 'vue-router';
import { toast } from 'vue3-toastify';
import RequestModal from '@/components/RequestModal.vue';

/** * Tipagem e Estado 
 */
interface TravelRequest {
  id: number;
  destination: string;
  departure_date: string;
  return_date: string;
  status: 'solicitado' | 'aprovado' | 'cancelado';
  requester_name: string;
}

const router = useRouter();
const auth = useAuthStore();
const isModalOpen = ref(false);
const requests = ref<TravelRequest[]>([]);
const loading = ref(true);

/**
 * Lógica de Negócio
 */
const loadRequests = async () => {
  try {
    loading.value = true;
    const response = await travelService.getAll();
    requests.value = response.data; // Conserta o erro da imagem (response.data)
  } catch (error) {
    toast.error('Erro ao carregar pedidos.');
  } finally {
    loading.value = false;
  }
};

const handleSuccess = () => {
  isModalOpen.value = false;
  loadRequests();
};

const approve = async (id: number) => {
  try {
    await travelService.approve(id);
    toast.success('Viagem aprovada com sucesso!');
    await loadRequests();
  } catch (e) {
    toast.error('Erro ao aprovar viagem.');
  }
};

const cancel = async (id: number) => {
  try {
    await travelService.cancel(id);
    toast.success('Viagem cancelada.');
    await loadRequests();
  } catch (e) {
    toast.error('Erro ao cancelar viagem.');
  }
};

const handleLogout = () => {
  auth.logout();
  router.push('/login');
};

/**
 * Formatadores e Helpers de UI
 */
const formatDate = (date: string) => {
  if (!date) return '---';
  return new Date(date).toLocaleDateString('pt-BR');
};

const statusClass = (status: string | undefined) => {
  const base = "border border-current transition-all";
  switch(status) {
    case 'approved': return `${base} bg-success-50 text-success-600`;
    case 'canceled': return `${base} bg-secondary-50 text-secondary-600`;
    case 'pending': return `${base} bg-warning-50 text-warning-600`;
    default: return 'bg-neutral-100 text-neutral-500';
  }
};

onMounted(async () => {
  await auth.getUser(); 
  loadRequests();
});
</script>
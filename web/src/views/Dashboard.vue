<template>
  <div v-if="auth.isInitialLoading && !auth.user" class="min-h-screen flex flex-col items-center justify-center bg-neutral-50">
    <img src="/favicon.ico" class="w-12 h-12 animate-spin mb-4" alt="Carregando..." />
    <p class="text-neutral-500 font-medium animate-pulse">Restaurando sessão...</p>
  </div>

  <div v-else class="min-h-screen bg-neutral-50 font-sans">
    <nav class="bg-white shadow-sm border-b border-neutral-200 sticky top-0 z-10 transition-all">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-2 lg:py-0 min-h-[64px] lg:h-16 flex flex-col lg:flex-row items-center justify-between gap-4 lg:gap-0">
        <img src="@/assets/logo-tripuai.png" alt="TripUAI" class="h-8 lg:h-12 w-auto transition-all" />
        
        <div class="flex items-center gap-6">
          <div v-if="auth.user" class="flex items-center gap-3">
            <span v-if="auth.isAdmin" class="bg-primary-50 text-primary-700 text-[10px] tracking-wider font-bold px-2.5 py-0.5 rounded-full border border-primary-100">
              ADMIN
            </span>
            <!-- Mail Icon for Admins -->
            <button 
              v-if="auth.isAdmin"
              @click="isBroadcastModalOpen = true"
              class="text-neutral-400 hover:text-primary-600 transition-colors p-1"
              title="Enviar e-mail para todos"
            >
              <Mail class="w-5 h-5" />
            </button>
            <div class="flex flex-col items-end">
              <span class="text-neutral-900 text-sm font-semibold leading-none">{{ auth.user?.name }}</span>
            </div>
          </div>
          <button @click="handleLogout" class="text-secondary-600 hover:text-secondary-700 text-sm font-bold transition-colors">
            Sair
          </button>
        </div>
      </div>
    </nav>

    <main class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
      <div class="mb-8 flex flex-col lg:flex-row justify-between items-start lg:items-end gap-4 lg:gap-0">
        <div>
          <h1 class="text-3xl font-extrabold text-neutral-900 tracking-tight">Solicitações de Viagem</h1>
          <p class="text-neutral-500 mt-1">Gerencie e acompanhe seus pedidos de deslocamento corporativo.</p>
        </div>
        
        <div class="flex items-center gap-4">
          <div :class="{ 'invisible': !loading }" class="flex items-center gap-2 mr-2">
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

      <TravelFilters @filter="handleFilterChange" />

      <div :class="{ 'invisible': !loading }" class="flex items-center justify-center gap-3 mb-6 h-8">
        <img src="/favicon.ico" class="w-8 h-8 animate-spin" />
        <span class="text-neutral-500 font-bold italic text-lg">Aguarde...</span>
      </div>

      <Pagination 
        :meta="pagination"
        @change-page="fetchRequests"
      />

      <!-- Mobile Card View (Visible < lg) -->
      <div class="block lg:hidden space-y-4 mb-6">
        <div v-for="request in requests" :key="request.id" class="bg-white p-5 rounded-xl shadow-sm border border-neutral-200">
          <div class="flex justify-between items-start mb-4">
            <div>
              <span class="text-[10px] font-black text-neutral-400 uppercase tracking-widest block mb-1">Solicitante</span>
              <p class="font-bold text-neutral-900 text-sm">{{ request.requester_name || '---' }}</p>
            </div>
            <span :class="statusClass(request.status)" class="px-2 py-1 text-[10px] font-black rounded uppercase">
              {{ request.status === 'solicitado' ? 'Solicitado' : (request.status === 'aprovado' ? 'Aprovado' : 'Cancelado') }}
            </span>
          </div>

          <div class="mb-4">
            <span class="text-[10px] font-black text-neutral-400 uppercase tracking-widest block mb-1">Destino</span>
            <div class="flex items-center gap-1 text-sm text-neutral-700 font-medium">
               <MapPin class="w-4 h-4 text-primary-500 flex-shrink-0" />
               {{ request.destination }}
            </div>
          </div>

          <div class="mb-4">
            <span class="text-[10px] font-black text-neutral-400 uppercase tracking-widest block mb-1">Período</span>
            <p class="text-sm text-neutral-600 font-medium">
              {{ formatDate(request.departure_date) }} <span class="text-neutral-300 mx-1">→</span> {{ formatDate(request.return_date) }}
            </p>
          </div>

          <div class="pt-4 border-t border-neutral-100 flex items-center justify-end gap-3">
             <button 
                @click="openViewModal(request)" 
                class="flex items-center gap-2 px-3 py-2 bg-primary-50 text-primary-700 rounded-lg text-xs font-bold hover:bg-primary-100 transition-colors"
             >
               <Eye class="w-4 h-4" /> Detalhes
             </button>

             <template v-if="auth.isAdmin && request.status === 'solicitado'">
               <button 
                  @click="openConfirm(request, 'approve')" 
                  class="flex items-center gap-2 px-3 py-2 bg-success-50 text-success-700 rounded-lg text-xs font-bold hover:bg-success-100 transition-colors"
                >
                  <Check class="w-4 h-4" /> Aprovar
               </button>
               <button 
                  @click="openConfirm(request, 'cancel')" 
                  class="flex items-center gap-2 px-3 py-2 bg-secondary-50 text-secondary-700 rounded-lg text-xs font-bold hover:bg-secondary-100 transition-colors"
                >
                  <X class="w-4 h-4" /> Recusar
               </button>
             </template>
          </div>
        </div>
        
        <div v-if="requests.length === 0 && !loading" class="text-center py-8 text-neutral-400 italic bg-white rounded-xl border border-neutral-200">
           Nenhuma solicitação encontrada.
        </div>
      </div>

      <!-- Desktop Table View (Visible >= lg) -->
      <div class="hidden lg:block bg-white shadow-xl shadow-neutral-200/50 rounded-xl overflow-hidden border border-neutral-200">
        <table class="min-w-full divide-y divide-neutral-200">
          <thead class="bg-neutral-50">
            <tr>
              <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-neutral-500 uppercase tracking-widest w-[25%]">Solicitante</th>
              <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-neutral-500 uppercase tracking-widest w-[25%]">Destino</th>
              <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-neutral-500 uppercase tracking-widest w-[20%]">Período</th>
              <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-neutral-500 uppercase tracking-widest w-[15%]">Status</th>
              <th scope="col" class="px-6 py-4 text-right text-xs font-bold text-neutral-500 uppercase tracking-widest w-[15%]">Ações</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-neutral-200 bg-white">
            <tr v-for="request in requests" :key="request.id" class="hover:bg-neutral-50/80 transition-colors group">
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm font-bold text-neutral-900">{{ request.requester_name || '---' }}</div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap max-w-[200px]">
                <div class="text-sm text-neutral-600 flex items-center gap-1 overflow-hidden text-ellipsis shadow-none" title="Clique em visualizar para ver o endereço completo">
                  <MapPin class="w-4 h-4 text-primary-500 flex-shrink-0" />
                  <span class="truncate">{{ request.destination }}</span>
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
              <td class="px-6 py-4 text-right whitespace-nowrap text-sm font-medium">
                <div class="flex items-center justify-end gap-3">
                  <button 
                    @click="openViewModal(request)" 
                    class="text-primary-600 hover:text-primary-700 hover:bg-primary-50 p-1.5 rounded-lg transition-all flex-shrink-0"
                    title="Ver Detalhes"
                  >
                    <Eye class="w-5 h-5" />
                  </button>

                  <div v-if="auth.isAdmin" class="flex items-center justify-end gap-2 w-[100px]">
                    <template v-if="request.status === 'solicitado'">
                      <span class="text-neutral-200 h-6 border-l border-neutral-200 mx-1"></span>
                      <button 
                        @click="openConfirm(request, 'approve')" 
                        class="text-success-600 hover:text-success-700 hover:bg-success-50 p-1.5 rounded-lg transition-all"
                        title="Aprovar Solicitação"
                      >
                        <Check class="w-5 h-5" />
                      </button>
                      <button 
                        @click="openConfirm(request, 'cancel')" 
                        class="text-secondary-600 hover:text-secondary-700 hover:bg-secondary-50 p-1.5 rounded-lg transition-all"
                        title="Recusar Solicitação"
                      >
                        <X class="w-5 h-5" />
                      </button>
                    </template>
                    
                    <span v-else class="text-neutral-300 italic text-[10px] font-medium bg-neutral-50 px-2 py-1 rounded w-full text-center">
                      Concluído
                    </span>
                  </div>
                </div>
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

    <ConfirmModal 
      :is-open="isConfirmOpen"
      :title="confirmData.title"
      :message="confirmData.message"
      :variant="confirmData.variant"
      @close="isConfirmOpen = false"
      @confirm="handleConfirmAction"
    />

    <ViewDetailsModal
      :is-open="isViewModalOpen"
      :request="viewModalRequest"
      @close="isViewModalOpen = false"
    />

    <BroadcastEmailModal
      :is-open="isBroadcastModalOpen"
      @close="isBroadcastModalOpen = false"
      @send="handleBroadcastSend"
    />
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue';
import { useAuthStore } from '@/stores/auth';
import { useRouter } from 'vue-router';
import { toast } from 'vue3-toastify';
import { useTravelRequests, type TravelRequest } from '@/composables/useTravelRequests';
import RequestModal from '@/components/RequestModal.vue';
import ConfirmModal from '@/components/ConfirmModal.vue';
import TravelFilters from '@/components/TravelFilters.vue';
import Pagination from '@/components/Pagination.vue';
import ViewDetailsModal from '@/components/ViewDetailsModal.vue';
import BroadcastEmailModal from '@/components/BroadcastEmailModal.vue';
import { Eye, Check, X, MapPin, Mail } from 'lucide-vue-next';
import { travelService } from '@/services/travelService';

const router = useRouter();
const auth = useAuthStore();
const { requests, loading, pagination, fetchRequests, handleFilterChange, approveRequest, cancelRequest } = useTravelRequests();

const isModalOpen = ref(false);

// Estado para o Modal de Confirmação
const isConfirmOpen = ref(false);
const confirmData = ref({ 
  id: 0, 
  type: '' as 'approve' | 'cancel' | 'broadcast', 
  title: '', 
  message: '', 
  variant: 'success' as 'success' | 'danger' 
});

// Estado para o Modal de Visualização
const isViewModalOpen = ref(false);
const viewModalRequest = ref<any>({});

// Estado para o Modal de Email Broadcast
const isBroadcastModalOpen = ref(false);

const handleSuccess = () => {
  isModalOpen.value = false;
  fetchRequests(pagination.value.current_page);
};

const openConfirm = (request: TravelRequest, type: 'approve' | 'cancel') => {
  confirmData.value = {
    id: request.id,
    type: type,
    title: type === 'approve' ? 'Aprovar Viagem' : 'Cancelar Viagem',
    message: `Deseja realmente ${type === 'approve' ? 'aprovar' : 'cancelar'} a solicitação para ${request.destination}?`,
    variant: type === 'approve' ? 'success' : 'danger'
  };
  isConfirmOpen.value = true;
};

const openViewModal = (request: TravelRequest) => {
  viewModalRequest.value = request;
  isViewModalOpen.value = true;
};

const handleConfirmAction = async () => {
  isConfirmOpen.value = false;
  if (confirmData.value.type === 'broadcast') {
    await confirmBroadcast();
  } else if (confirmData.value.type === 'approve') {
    await approveRequest(confirmData.value.id);
  } else {
    await cancelRequest(confirmData.value.id);
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
  // A API agora retorna dd/mm/yyyy string, ou null. 
  // Se for string dd/mm/yyyy não precisa de new Date(). 
  // Mas como estamos no transition, vamos verificar.
  // Se vier no formato ISO do backend, converte. Se vier formatado, exibe.
  // Pela refatoração do backend API Resource, JÁ VEM 'dd/mm/yyyy'.
  return date; 
};

const statusClass = (status: string | undefined) => {
  const base = "border border-current transition-all";
  switch(status) {
    case 'aprovado': return `${base} bg-success-50 text-success-600 border-success-200`;
    case 'cancelado': return `${base} bg-secondary-50 text-secondary-600 border-secondary-200`;
    case 'solicitado': return `${base} bg-warning-50 text-warning-600 border-warning-200`;
    default: return 'bg-neutral-100 text-neutral-500 border-neutral-200';
  }
};

const handleBroadcastSend = (payload: { subject: string, body: string }) => {
  isBroadcastModalOpen.value = false;
  confirmData.value = {
    id: 0, 
    type: 'broadcast', 
    title: 'Enviar Mensagem para Todos',
    message: 'Tem certeza que deseja enviar este e-mail para TODOS os usuários do sistema?',
    variant: 'success'
  };
  pendingBroadcastPayload.value = payload;
  isConfirmOpen.value = true;
};

const pendingBroadcastPayload = ref<{ subject: string, body: string } | null>(null);

const confirmBroadcast = async () => {
  try {
    if (!pendingBroadcastPayload.value) return;

    await travelService.sendBroadcast(pendingBroadcastPayload.value);
    
    toast.success('Envio processando em segundo plano.');
    pendingBroadcastPayload.value = null; 
  } catch (error) {
    toast.error('Erro ao enviar mensagem.');
  }
};

onMounted(async () => {
  await auth.getUser(); 
  fetchRequests();
});
</script>
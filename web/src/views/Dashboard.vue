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

      <!-- Mobile List -->
      <TravelRequestMobileList
        :requests="requests"
        :loading="loading"
        :is-admin="auth.isAdmin"
        @view="openViewModal"
        @approve="(req) => openConfirm(req, 'approve')"
        @cancel="(req) => openConfirm(req, 'cancel')"
      />

      <!-- Desktop Table -->
      <TravelRequestTable 
        :requests="requests"
        :loading="loading"
        :is-admin="auth.isAdmin"
        @view="openViewModal"
        @approve="(req) => openConfirm(req, 'approve')"
        @cancel="(req) => openConfirm(req, 'cancel')"
      />

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
import { useTravelRequests } from '@/composables/useTravelRequests';
import type { TravelRequest } from '@/types/TravelRequest';
import RequestModal from '@/components/RequestModal.vue';
import ConfirmModal from '@/components/ConfirmModal.vue';
import TravelFilters from '@/components/TravelFilters.vue';
import Pagination from '@/components/Pagination.vue';
import ViewDetailsModal from '@/components/ViewDetailsModal.vue';
import BroadcastEmailModal from '@/components/BroadcastEmailModal.vue';
import { Mail } from 'lucide-vue-next';
import { travelService } from '@/services/travelService';
import TravelRequestTable from '@/components/TravelRequestTable.vue';
import TravelRequestMobileList from '@/components/TravelRequestMobileList.vue';

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
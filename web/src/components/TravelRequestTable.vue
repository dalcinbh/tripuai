<template>
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
                @click="emit('view', request)" 
                class="text-primary-600 hover:text-primary-700 hover:bg-primary-50 p-1.5 rounded-lg transition-all flex-shrink-0"
                title="Ver Detalhes"
              >
                <Eye class="w-5 h-5" />
              </button>

              <div v-if="isAdmin" class="flex items-center justify-end gap-2 w-[100px]">
                <template v-if="request.status === 'solicitado'">
                  <span class="text-neutral-200 h-6 border-l border-neutral-200 mx-1"></span>
                  <button 
                    @click="emit('approve', request)" 
                    class="text-success-600 hover:text-success-700 hover:bg-success-50 p-1.5 rounded-lg transition-all"
                    title="Aprovar Solicitação"
                  >
                    <Check class="w-5 h-5" />
                  </button>
                  <button 
                    @click="emit('cancel', request)" 
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
</template>

<script setup lang="ts">
import { PropType } from 'vue';
import { Eye, Check, X, MapPin } from 'lucide-vue-next';
import type { TravelRequest } from '@/composables/useTravelRequests';

defineProps({
  requests: {
    type: Array as PropType<TravelRequest[]>,
    required: true
  },
  loading: {
    type: Boolean,
    default: false
  },
  isAdmin: {
    type: Boolean,
    default: false
  }
});

const emit = defineEmits(['view', 'approve', 'cancel']);

const formatDate = (date: string) => {
  if (!date) return '---';
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
</script>

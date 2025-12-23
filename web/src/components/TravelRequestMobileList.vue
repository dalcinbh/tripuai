<template>
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
            @click="emit('view', request)" 
            class="flex items-center gap-2 px-3 py-2 bg-primary-50 text-primary-700 rounded-lg text-xs font-bold hover:bg-primary-100 transition-colors"
         >
           <Eye class="w-4 h-4" /> Detalhes
         </button>

         <template v-if="isAdmin && request.status === 'solicitado'">
           <button 
              @click="emit('approve', request)" 
              class="flex items-center gap-2 px-3 py-2 bg-success-50 text-success-700 rounded-lg text-xs font-bold hover:bg-success-100 transition-colors"
            >
              <Check class="w-4 h-4" /> Aprovar
           </button>
           <button 
              @click="emit('cancel', request)" 
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

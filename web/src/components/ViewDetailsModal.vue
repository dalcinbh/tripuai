<template>
  <div v-if="isOpen" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-neutral-900/60 backdrop-blur-sm">
    <div class="bg-white rounded-2xl shadow-2xl w-[95%] sm:w-full sm:max-w-lg overflow-hidden border border-neutral-200">
      <div class="px-6 py-4 border-b border-neutral-100 flex justify-between items-center bg-neutral-50/50">
        <h3 class="text-xl font-bold text-neutral-900">Detalhes da Viagem</h3>
        <button @click="emit('close')" class="text-neutral-400 hover:text-neutral-600 text-2xl">&times;</button>
      </div>

      <div class="p-6 space-y-6">
        <div class="grid grid-cols-2 gap-6">
          <div>
            <label class="block text-[10px] font-black text-neutral-400 uppercase tracking-widest">Solicitante</label>
            <p class="text-sm font-bold text-neutral-900">{{ request.requester_name }}</p>
          </div>
          <div>
            <label class="block text-[10px] font-black text-neutral-400 uppercase tracking-widest">Status</label>
            <span :class="statusClass(request.status)" class="px-2 py-0.5 text-[10px] font-black rounded uppercase">
              {{ request.status }}
            </span>
          </div>
        </div>

        <div>
          <label class="block text-[10px] font-black text-neutral-400 uppercase tracking-widest">Destino Completo</label>
          <p class="text-sm text-neutral-700 bg-neutral-50 p-3 rounded-lg border border-neutral-100 italic">
            📍 {{ request.destination }}
          </p>
        </div>

        <div class="grid grid-cols-2 gap-6">
          <div>
            <label class="block text-[10px] font-black text-neutral-400 uppercase tracking-widest">Data de Ida</label>
            <p class="text-sm text-neutral-700">{{ formatDate(request.departure_date) }}</p>
          </div>
          <div>
            <label class="block text-[10px] font-black text-neutral-400 uppercase tracking-widest">Data de Volta</label>
            <p class="text-sm text-neutral-700">{{ formatDate(request.return_date) }}</p>
          </div>
        </div>
      </div>

      <div class="px-6 py-4 bg-neutral-50 border-t border-neutral-100 flex justify-end">
        <button @click="emit('close')" class="px-6 py-2 bg-white border border-neutral-200 text-neutral-600 rounded-lg font-bold text-sm hover:bg-neutral-100 transition-all">
          Fechar
        </button>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
defineProps<{ isOpen: boolean; request: any }>();
const emit = defineEmits(['close']);

const formatDate = (date: string) => new Date(date).toLocaleDateString('pt-BR');
const statusClass = (status: string) => {
  if (status === 'aprovado') return 'bg-success-50 text-success-600';
  if (status === 'cancelado') return 'bg-secondary-50 text-secondary-600';
  return 'bg-warning-50 text-warning-600';
};
</script>
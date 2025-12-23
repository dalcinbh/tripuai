<template>
  <div v-if="isOpen" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-neutral-900/60 backdrop-blur-sm">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md overflow-hidden border border-neutral-200 animate-in fade-in zoom-in duration-200">
      
      <div class="px-6 py-4 border-b border-neutral-100 flex justify-between items-center bg-neutral-50/50">
        <h3 class="text-xl font-bold text-neutral-900">Nova Solicitação</h3>
        <button @click="emit('close')" class="text-neutral-400 hover:text-neutral-600 transition-colors text-2xl">&times;</button>
      </div>

      <form @submit.prevent="handleSubmit" class="p-6 space-y-4">
        <div>
          <label class="block text-xs font-black text-neutral-500 uppercase tracking-widest mb-1">Nome do Solicitante</label>
          <input 
            v-model="form.requester_name"
            type="text" 
            required
            placeholder="Quem irá viajar?"
            class="w-full px-4 py-2.5 rounded-lg border border-neutral-200 focus:ring-2 focus:ring-primary-500 outline-none transition-all text-neutral-900 placeholder:text-neutral-300 font-sans"
          />
        </div>

        <div>
          <label class="block text-xs font-black text-neutral-500 uppercase tracking-widest mb-1">Destino</label>
          <input 
            v-model="form.destination"
            type="text" 
            required
            placeholder="Ex: Paris, França"
            class="w-full px-4 py-2.5 rounded-lg border border-neutral-200 focus:ring-2 focus:ring-primary-500 outline-none transition-all text-neutral-900 placeholder:text-neutral-300 font-sans"
          />
        </div>

        <div class="grid grid-cols-2 gap-4">
          <div>
            <label class="block text-xs font-black text-neutral-500 uppercase tracking-widest mb-1">Data de Ida</label>
            <input 
              v-model="form.departure_date"
              type="date" 
              required
              class="w-full px-4 py-2.5 rounded-lg border border-neutral-200 focus:ring-2 focus:ring-primary-500 outline-none transition-all text-neutral-700 font-sans"
            />
          </div>
          <div>
            <label class="block text-xs font-black text-neutral-500 uppercase tracking-widest mb-1">Data de Volta</label>
            <input 
              v-model="form.return_date"
              type="date" 
              required
              class="w-full px-4 py-2.5 rounded-lg border border-neutral-200 focus:ring-2 focus:ring-primary-500 outline-none transition-all text-neutral-700 font-sans"
            />
          </div>
        </div>

        <div class="pt-4 flex flex-col gap-3">
          <button 
            type="submit" 
            :disabled="loading"
            class="w-full bg-primary-600 text-white py-3 rounded-xl font-bold hover:bg-primary-700 disabled:opacity-50 transition-all shadow-lg shadow-primary-200 flex items-center justify-center gap-2 min-w-[140px]"
          >
            <img v-if="loading" src="/favicon.ico" class="w-5 h-5 animate-spin" />
            {{ loading ? 'Enviando...' : 'Solicitar' }}
          </button>
          
          <button 
            type="button" 
            @click="emit('close')"
            class="w-full bg-transparent text-neutral-500 py-2 text-sm font-semibold hover:text-neutral-700"
          >
            Cancelar
          </button>
        </div>
      </form>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, reactive } from 'vue';
import { travelService } from '@/services/travelService';
import { toast } from 'vue3-toastify';

const props = defineProps<{ isOpen: boolean }>();
const emit = defineEmits(['close', 'success']);

const loading = ref(false);
const form = reactive({
  requester_name: '', // Adicionado ao estado reativo
  destination: '',
  departure_date: '',
  return_date: '',
});

const handleSubmit = async () => {
  loading.value = true;
  try {
    await travelService.create(form);
    toast.success('Viagem solicitada com sucesso!');
    emit('success');
  } catch (error: any) {
    const msg = error.response?.data?.message || 'Erro ao solicitar viagem.';
    toast.error(msg);
  } finally {
    loading.value = false;
  }
};
</script>
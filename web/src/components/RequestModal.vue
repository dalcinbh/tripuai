<script setup lang="ts">
import { ref, reactive } from 'vue';
import { travelService } from '@/services/travelService';
import { toast } from 'vue3-toastify';

const props = defineProps<{
  isOpen: boolean;
}>();

const emit = defineEmits(['close', 'success']);

const form = reactive({
  destination: '',
  start_date: '',
  end_date: '',
});

const loading = ref(false);

const handleSubmit = async () => {
  // --- A REGRA DE OURO ---
  if (new Date(form.end_date) < new Date(form.start_date)) {
    toast.error('A data de volta não pode ser anterior à data de ida!');
    return;
  }

  loading.value = true;
  try {
    await travelService.create(form);
    toast.success('Solicitação enviada com sucesso!');
    emit('success');
    resetForm();
  } catch (error: any) {
    toast.error(error.response?.data?.message || 'Erro ao enviar solicitação');
  } finally {
    loading.value = false;
  }
};

const resetForm = () => {
  form.destination = '';
  form.start_date = '';
  form.end_date = '';
  emit('close');
};
</script>

<template>
  <div v-if="isOpen" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-md p-6 border border-neutral-200">
      <div class="flex justify-between items-center mb-6">
        <h2 class="text-xl font-bold text-neutral-900">Nova Solicitação</h2>
        <button @click="emit('close')" class="text-neutral-400 hover:text-neutral-600">&times;</button>
      </div>

      <form @submit.prevent="handleSubmit" class="space-y-4">
        <div>
          <label class="block text-sm font-medium text-neutral-700 mb-1">Destino</label>
          <input v-model="form.destination" type="text" required placeholder="Ex: Paris, França"
            class="w-full px-4 py-2 rounded-lg border border-neutral-300 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none transition" />
        </div>

        <div class="grid grid-cols-2 gap-4">
          <div>
            <label class="block text-sm font-medium text-neutral-700 mb-1">Data de Ida</label>
            <input v-model="form.start_date" type="date" required
              class="w-full px-4 py-2 rounded-lg border border-neutral-300 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none transition" />
          </div>
          <div>
            <label class="block text-sm font-medium text-neutral-700 mb-1">Data de Volta</label>
            <input v-model="form.end_date" type="date" required
              class="w-full px-4 py-2 rounded-lg border border-neutral-300 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none transition" />
          </div>
        </div>

        <div class="pt-4 flex gap-3">
          <button type="button" @click="emit('close')" 
            class="flex-1 px-4 py-2 border border-neutral-300 text-neutral-700 rounded-lg hover:bg-neutral-50 transition font-medium">
            Cancelar
          </button>
          <button type="submit" :disabled="loading"
            class="flex-1 px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition font-medium disabled:opacity-50">
            {{ loading ? 'Enviando...' : 'Solicitar' }}
          </button>
        </div>
      </form>
    </div>
  </div>
</template>
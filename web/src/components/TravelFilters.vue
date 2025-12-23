<script setup lang="ts">
import { reactive } from 'vue';

const emit = defineEmits(['filter']);

const filters = reactive({
  destination: '',
  status: '',
  start_date: '',
  end_date: ''
});

const handleSearch = () => {
  // Envia uma cópia dos filtros para o Dashboard processar a requisição
  emit('filter', { ...filters });
};

const clearFilters = () => {
  filters.destination = '';
  filters.status = '';
  filters.start_date = '';
  filters.end_date = '';
  handleSearch();
};
</script>

<template>
  <div class="bg-white p-6 rounded-xl border border-neutral-200 shadow-sm mb-6">
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 items-end">
      <div class="flex flex-col gap-1">
        <label class="text-xs font-black text-neutral-500 uppercase tracking-widest">Destino</label>
        <input 
          v-model="filters.destination" 
          type="text" 
          placeholder="Ex: Paris" 
          class="px-4 py-2 rounded-lg border border-neutral-200 focus:ring-2 focus:ring-primary-500 outline-none text-sm font-sans"
        />
      </div>

      <div class="flex flex-col gap-1">
        <label class="text-xs font-black text-neutral-500 uppercase tracking-widest">Status</label>
        <select 
          v-model="filters.status" 
          class="px-4 py-2 rounded-lg border border-neutral-200 focus:ring-2 focus:ring-primary-500 outline-none text-sm font-sans bg-white cursor-pointer"
        >
          <option value="">Todos os status</option>
          <option value="solicitado">Solicitado</option>
          <option value="aprovado">Aprovado</option>
          <option value="cancelado">Cancelado</option>
        </select>
      </div>

      <div class="flex flex-col gap-1 md:col-span-1">
        <label class="text-xs font-black text-neutral-500 uppercase tracking-widest">Período (Início / Fim)</label>
        <div class="flex items-center gap-2">
          <input v-model="filters.start_date" type="date" class="w-full px-2 py-2 rounded-lg border border-neutral-200 text-xs font-sans outline-none" />
          <span class="text-neutral-300">|</span>
          <input v-model="filters.end_date" type="date" class="w-full px-2 py-2 rounded-lg border border-neutral-200 text-xs font-sans outline-none" />
        </div>
      </div>

      <div class="flex items-center gap-2">
        <button 
          @click="handleSearch" 
          class="flex-1 bg-neutral-900 text-white px-4 py-2.5 rounded-lg font-bold text-sm hover:bg-neutral-800 transition-all flex items-center justify-center gap-2"
        >
          🔍 Filtrar
        </button>
        <button 
          @click="clearFilters" 
          class="px-4 py-2.5 text-sm font-bold text-neutral-400 hover:text-secondary-600 transition-colors"
        >
          Limpar
        </button>
      </div>
    </div>
  </div>
</template>
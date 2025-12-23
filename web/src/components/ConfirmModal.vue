<template>
  <div v-if="isOpen" class="fixed inset-0 z-[60] flex items-center justify-center p-4 bg-neutral-900/60 backdrop-blur-sm">
    <div class="bg-white rounded-2xl shadow-2xl w-[95%] sm:w-full sm:max-w-sm p-6 border border-neutral-200 animate-in fade-in zoom-in duration-200">
      <div class="flex items-center justify-center w-12 h-12 mx-auto mb-4 rounded-full" :class="variant === 'danger' ? 'bg-secondary-50' : 'bg-success-50'">
        <span class="text-2xl" :class="variant === 'danger' ? 'text-secondary-600' : 'text-success-600'">
          {{ variant === 'danger' ? '✕' : '✓' }}
        </span>
      </div>
      
      <h3 class="text-lg font-bold text-center text-neutral-900 mb-2">{{ title }}</h3>
      <p class="text-sm text-center text-neutral-500 mb-6">{{ message }}</p>

      <div class="flex gap-3">
        <button @click="emit('close')" class="flex-1 px-4 py-2 text-sm font-bold text-neutral-500 hover:bg-neutral-100 rounded-lg transition-colors">
          Cancelar
        </button>
        <button 
          @click="emit('confirm')" 
          class="flex-1 px-4 py-2 text-sm font-bold text-white rounded-lg shadow-lg transition-all"
          :class="variant === 'danger' ? 'bg-secondary-600 hover:bg-secondary-700 shadow-secondary-200' : 'bg-success-600 hover:bg-success-700 shadow-success-200'"
        >
          Confirmar
        </button>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
defineProps<{
  isOpen: boolean;
  title: string;
  message: string;
  variant: 'success' | 'danger';
}>();

const emit = defineEmits(['close', 'confirm']);
</script>
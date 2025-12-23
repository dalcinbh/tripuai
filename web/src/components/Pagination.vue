<template>
  <div v-if="meta.total > 0" class="flex items-center justify-between border-t border-neutral-200 bg-white px-4 py-3 sm:px-6 mt-4 rounded-b-xl">
    <div class="flex flex-1 justify-between sm:hidden">
      <button 
        @click="changePage(meta.current_page - 1)" 
        :disabled="meta.current_page === 1"
        class="relative inline-flex items-center rounded-md border border-neutral-300 bg-white px-4 py-2 text-sm font-medium text-neutral-700 hover:bg-neutral-50 disabled:opacity-50 disabled:cursor-not-allowed"
      >
        Anterior
      </button>
      <button 
        @click="changePage(meta.current_page + 1)" 
        :disabled="meta.current_page === meta.last_page"
        class="relative ml-3 inline-flex items-center rounded-md border border-neutral-300 bg-white px-4 py-2 text-sm font-medium text-neutral-700 hover:bg-neutral-50 disabled:opacity-50 disabled:cursor-not-allowed"
      >
        Próximo
      </button>
    </div>
    
    <div class="hidden sm:flex sm:flex-1 sm:items-center sm:justify-between">
      <div>
        <p class="text-sm text-neutral-700">
          Mostrando
          <span class="font-bold">{{ meta.from }}</span>
          a
          <span class="font-bold">{{ meta.to }}</span>
          de
          <span class="font-bold">{{ meta.total }}</span>
          resultados
        </p>
      </div>
      <div>
        <nav class="isolate inline-flex -space-x-px rounded-md shadow-sm" aria-label="Pagination">
          
          <!-- Previous -->
          <button 
            @click="changePage(meta.current_page - 1)"
            :disabled="meta.current_page === 1"
            class="relative inline-flex items-center rounded-l-md px-2 py-2 text-neutral-400 ring-1 ring-inset ring-neutral-300 hover:bg-neutral-50 focus:z-20 focus:outline-offset-0 disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
          >
            <span class="sr-only">Anterior</span>
            <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
              <path fill-rule="evenodd" d="M12.79 5.23a.75.75 0 01-.02 1.06L8.832 10l3.938 3.71a.75.75 0 11-1.04 1.08l-4.5-4.25a.75.75 0 010-1.08l4.5-4.25a.75.75 0 011.06.02z" clip-rule="evenodd" />
            </svg>
          </button>

          <!-- Pages (Simplified for range. For full logic we can iterate, but let's stick to simple window or just standard Links if provided, but manual loop is safer for arbitrary formatting) -->
          <!-- Standard Logic: show all if small, or sliding window -->
          <template v-for="page in pages" :key="page">
            <button
              v-if="page !== '...'"
              @click="changePage(page)"
              :class="[
                page === meta.current_page 
                  ? 'z-10 bg-primary-600 text-white focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary-600' 
                  : 'text-neutral-900 ring-1 ring-inset ring-neutral-300 hover:bg-neutral-50 focus:outline-offset-0',
                'relative inline-flex items-center px-4 py-2 text-sm font-semibold focus:z-20 transition-colors'
              ]"
            >
              {{ page }}
            </button>
            <span v-else class="relative inline-flex items-center px-4 py-2 text-sm font-semibold text-neutral-700 ring-1 ring-inset ring-neutral-300 focus:outline-offset-0">
              ...
            </span>
          </template>

          <!-- Next -->
          <button 
            @click="changePage(meta.current_page + 1)"
            :disabled="meta.current_page === meta.last_page"
            class="relative inline-flex items-center rounded-r-md px-2 py-2 text-neutral-400 ring-1 ring-inset ring-neutral-300 hover:bg-neutral-50 focus:z-20 focus:outline-offset-0 disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
          >
            <span class="sr-only">Próximo</span>
            <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
              <path fill-rule="evenodd" d="M7.21 14.77a.75.75 0 01.02-1.06L11.168 10 7.23 6.29a.75.75 0 111.04-1.08l4.5 4.25a.75.75 0 010 1.08l-4.5 4.25a.75.75 0 01-1.06-.02z" clip-rule="evenodd" />
            </svg>
          </button>
        </nav>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { computed } from 'vue';

const props = defineProps<{
  meta: {
    current_page: number;
    last_page: number;
    from: number;
    to: number;
    total: number;
  };
}>();

const emit = defineEmits(['change-page']);

const changePage = (page: number | string) => {
  if (typeof page === 'number' && page >= 1 && page <= props.meta.last_page) {
    emit('change-page', page);
  }
};

// Logic to show pages with ellipsis
const pages = computed(() => {
  const current = props.meta.current_page;
  const last = props.meta.last_page;
  const delta = 2;
  const range = [];
  const rangeWithDots: (number | string)[] = [];
  let l: number | undefined;

  range.push(1);
  for (let i = current - delta; i <= current + delta; i++) {
    if (i < last && i > 1) {
      range.push(i);
    }
  }
  range.push(last);

  for (let i of range) {
    if (l) {
      if (i - l === 2) {
        rangeWithDots.push(l + 1);
      } else if (i - l !== 1) {
        rangeWithDots.push('...');
      }
    }
    rangeWithDots.push(i);
    l = i;
  }

  return rangeWithDots;
});
</script>

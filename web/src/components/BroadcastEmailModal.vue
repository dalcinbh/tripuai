<template>
  <div v-if="isOpen" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-neutral-900/60 backdrop-blur-sm">
    <div class="bg-white rounded-2xl shadow-2xl w-[95%] sm:w-full sm:max-w-2xl overflow-hidden border border-neutral-200 animate-in fade-in zoom-in duration-200">
      
      <!-- Header -->
      <div class="px-6 py-4 border-b border-neutral-100 flex justify-between items-center bg-neutral-50/50">
        <div class="flex items-center gap-3">
          <div class="bg-primary-100 p-2 rounded-lg">
            <Mail class="w-5 h-5 text-primary-600" />
          </div>
          <div>
            <h3 class="text-xl font-bold text-neutral-900">Nova Mensagem para Todos</h3>
            <p class="text-xs text-neutral-500">Enviar e-mail para todos os usuários do sistema</p>
          </div>
        </div>
        <button @click="$emit('close')" class="text-neutral-400 hover:text-neutral-600 transition-colors text-2xl">&times;</button>
      </div>

      <!-- Body -->
      <div class="p-6 space-y-4">
        <!-- Assunto -->
        <div>
          <label class="block text-xs font-black text-neutral-500 uppercase tracking-widest mb-1">Assunto</label>
          <input 
            v-model="subject"
            type="text" 
            placeholder="Digite o assunto do e-mail..."
            class="w-full px-4 py-2.5 rounded-lg border border-neutral-200 focus:ring-2 focus:ring-primary-500 outline-none transition-all text-neutral-900 placeholder:text-neutral-300 font-sans font-bold"
          />
        </div>

        <!-- Editor Rico Simples -->
        <div>
          <label class="block text-xs font-black text-neutral-500 uppercase tracking-widest mb-1">Mensagem</label>
          <div class="border border-neutral-200 rounded-lg overflow-hidden focus-within:ring-2 focus-within:ring-primary-500 transition-all">
            <!-- Toolbar -->
            <div class="bg-neutral-50 border-b border-neutral-200 p-2 flex gap-2">
              <button @click="execCmd('bold')" class="p-1.5 hover:bg-neutral-200 rounded text-neutral-600" title="Negrito">
                <Bold class="w-4 h-4" />
              </button>
              <button @click="execCmd('italic')" class="p-1.5 hover:bg-neutral-200 rounded text-neutral-600" title="Itálico">
                <Italic class="w-4 h-4" />
              </button>
              <button @click="execCmd('underline')" class="p-1.5 hover:bg-neutral-200 rounded text-neutral-600" title="Sublinhado">
                <Underline class="w-4 h-4" />
              </button>
              <div class="w-px h-6 bg-neutral-300 mx-1"></div>
              <button @click="execCmd('insertUnorderedList')" class="p-1.5 hover:bg-neutral-200 rounded text-neutral-600" title="Lista">
                <List class="w-4 h-4" />
              </button>
            </div>
            
            <!-- Content Editable Area -->
            <div 
              ref="editor"
              contenteditable="true"
              class="w-full h-64 p-4 outline-none overflow-y-auto text-neutral-700 font-sans"
              @input="updateContent"
            ></div>
          </div>
        </div>
      </div>

      <!-- Footer -->
      <div class="px-6 py-4 border-t border-neutral-100 flex justify-end gap-3 bg-neutral-50/30">
        <button 
          @click="$emit('close')" 
          class="px-4 py-2 text-sm font-bold text-neutral-500 hover:text-neutral-700 transition-colors"
        >
          Cancelar
        </button>
        <button 
          @click="handleSend" 
          :disabled="!isValid"
          class="bg-primary-600 text-white px-6 py-2 rounded-lg font-bold hover:bg-primary-700 disabled:opacity-50 disabled:cursor-not-allowed transition-all shadow-md shadow-primary-200 flex items-center gap-2"
        >
          <Send class="w-4 h-4" />
          Enviar Mensagem
        </button>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, computed } from 'vue';
import { Mail, Bold, Italic, Underline, List, Send } from 'lucide-vue-next';

const props = defineProps<{ isOpen: boolean }>();
const emit = defineEmits(['close', 'send']);

const subject = ref('');
const content = ref('');
const editor = ref<HTMLElement | null>(null);

const isValid = computed(() => {
  return subject.value.trim().length > 0 && content.value.trim().length > 0;
});

const execCmd = (command: string) => {
  document.execCommand(command, false);
  editor.value?.focus();
};

const updateContent = (event: Event) => {
  content.value = (event.target as HTMLElement).innerHTML;
};

const handleSend = () => {
  if (isValid.value) {
    emit('send', {
      subject: subject.value,
      body: content.value
    });
  }
};
</script>

<style scoped>
/* Basic styles for contenteditable content */
:deep(ul) {
  list-style-type: disc;
  padding-left: 1.5rem;
}
:deep(ol) {
  list-style-type: decimal;
  padding-left: 1.5rem;
}
</style>

<template>
  <div class="border border-gray-300 rounded-lg">
    <!-- Toolbar -->
    <div class="border-b border-gray-200 p-2 flex items-center space-x-2">
      <button 
        @click="editor?.chain().focus().toggleBold().run()"
        :class="[editor?.isActive('bold') ? 'bg-gray-200' : '', 'p-2 rounded hover:bg-gray-100']"
        title="Bold"
      >
        <Bold class="h-4 w-4" />
      </button>
      
      <button 
        @click="editor?.chain().focus().toggleItalic().run()"
        :class="[editor?.isActive('italic') ? 'bg-gray-200' : '', 'p-2 rounded hover:bg-gray-100']"
        title="Italic"
      >
        <Italic class="h-4 w-4" />
      </button>
      
      <button 
        @click="editor?.chain().focus().toggleStrike().run()"
        :class="[editor?.isActive('strike') ? 'bg-gray-200' : '', 'p-2 rounded hover:bg-gray-100']"
        title="Strikethrough"
      >
        <Strikethrough class="h-4 w-4" />
      </button>
      
      <div class="w-px h-6 bg-gray-300"></div>
      
      <button 
        @click="editor?.chain().focus().toggleHeading({ level: 1 }).run()"
        :class="[editor?.isActive('heading', { level: 1 }) ? 'bg-gray-200' : '', 'p-2 rounded hover:bg-gray-100']"
        title="Heading 1"
      >
        H1
      </button>
      
      <button 
        @click="editor?.chain().focus().toggleHeading({ level: 2 }).run()"
        :class="[editor?.isActive('heading', { level: 2 }) ? 'bg-gray-200' : '', 'p-2 rounded hover:bg-gray-100']"
        title="Heading 2"
      >
        H2
      </button>
      
      <button 
        @click="editor?.chain().focus().toggleBulletList().run()"
        :class="[editor?.isActive('bulletList') ? 'bg-gray-200' : '', 'p-2 rounded hover:bg-gray-100']"
        title="Bullet List"
      >
        <List class="h-4 w-4" />
      </button>
      
      <button 
        @click="editor?.chain().focus().toggleOrderedList().run()"
        :class="[editor?.isActive('orderedList') ? 'bg-gray-200' : '', 'p-2 rounded hover:bg-gray-100']"
        title="Numbered List"
      >
        <ListOrdered class="h-4 w-4" />
      </button>
      
      <div class="w-px h-6 bg-gray-300"></div>
      
      <button 
        @click="editor?.chain().focus().toggleCodeBlock().run()"
        :class="[editor?.isActive('codeBlock') ? 'bg-gray-200' : '', 'p-2 rounded hover:bg-gray-100']"
        title="Code Block"
      >
        <Code class="h-4 w-4" />
      </button>
      
      <button 
        @click="editor?.chain().focus().toggleBlockquote().run()"
        :class="[editor?.isActive('blockquote') ? 'bg-gray-200' : '', 'p-2 rounded hover:bg-gray-100']"
        title="Quote"
      >
        <Quote class="h-4 w-4" />
      </button>
    </div>
    
    <!-- Editor Content -->
    <div class="p-4">
      <editor-content :editor="editor" class="prose max-w-none focus:outline-none" />
    </div>
    
    <!-- Export Actions -->
    <div class="border-t border-gray-200 p-2 flex justify-end space-x-2">
      <button 
        @click="exportToPDF"
        class="px-3 py-1 text-sm bg-red-500 text-white rounded hover:bg-red-600"
      >
        Export PDF
      </button>
      <button 
        @click="exportToDOCX"
        class="px-3 py-1 text-sm bg-blue-500 text-white rounded hover:bg-blue-600"
      >
        Export DOCX
      </button>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted, onBeforeUnmount, watch } from 'vue'
import { useEditor, EditorContent } from '@tiptap/vue-3'
import StarterKit from '@tiptap/starter-kit'
import Link from '@tiptap/extension-link'
import { Bold, Italic, Strikethrough, List, ListOrdered, Code, Quote } from 'lucide-vue-next'

interface Props {
  modelValue?: string
  placeholder?: string
}

const props = withDefaults(defineProps<Props>(), {
  modelValue: '',
  placeholder: 'Start writing...'
})

const emit = defineEmits<{
  'update:modelValue': [value: string]
}>()

const editor = useEditor({
  content: props.modelValue,
  extensions: [
    StarterKit,
    Link.configure({
      openOnClick: false,
    }),
  ],
  editorProps: {
    attributes: {
      class: 'prose prose-sm sm:prose lg:prose-lg xl:prose-2xl mx-auto focus:outline-none',
    },
  },
  onUpdate: ({ editor }) => {
    emit('update:modelValue', editor.getHTML())
  },
})

watch(() => props.modelValue, (newValue) => {
  if (editor.value && editor.value.getHTML() !== newValue) {
    editor.value.commands.setContent(newValue)
  }
})

const exportToPDF = async () => {
  try {
    const response = await fetch('/api/export/pdf', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
      },
      body: JSON.stringify({
        content: editor.value?.getHTML() || '',
      }),
    })
    
    if (response.ok) {
      const blob = await response.blob()
      const url = window.URL.createObjectURL(blob)
      const a = document.createElement('a')
      a.href = url
      a.download = 'document.pdf'
      document.body.appendChild(a)
      a.click()
      window.URL.revokeObjectURL(url)
      document.body.removeChild(a)
    }
  } catch (error) {
    console.error('Error exporting PDF:', error)
  }
}

const exportToDOCX = async () => {
  try {
    const response = await fetch('/api/export/docx', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
      },
      body: JSON.stringify({
        content: editor.value?.getHTML() || '',
      }),
    })
    
    if (response.ok) {
      const blob = await response.blob()
      const url = window.URL.createObjectURL(blob)
      const a = document.createElement('a')
      a.href = url
      a.download = 'document.docx'
      document.body.appendChild(a)
      a.click()
      window.URL.revokeObjectURL(url)
      document.body.removeChild(a)
    }
  } catch (error) {
    console.error('Error exporting DOCX:', error)
  }
}

onBeforeUnmount(() => {
  editor.value?.destroy()
})
</script>

<style>
.ProseMirror {
  outline: none;
  min-height: 200px;
}

.ProseMirror p.is-editor-empty:first-child::before {
  content: attr(data-placeholder);
  float: left;
  color: #adb5bd;
  pointer-events: none;
  height: 0;
}

.ProseMirror h1 {
  font-size: 2em;
  font-weight: bold;
  margin: 0.67em 0;
}

.ProseMirror h2 {
  font-size: 1.5em;
  font-weight: bold;
  margin: 0.83em 0;
}

.ProseMirror h3 {
  font-size: 1.17em;
  font-weight: bold;
  margin: 1em 0;
}

.ProseMirror blockquote {
  border-left: 4px solid #e5e7eb;
  padding-left: 1rem;
  margin: 1rem 0;
  font-style: italic;
}

.ProseMirror code {
  background-color: #f3f4f6;
  padding: 0.2em 0.4em;
  border-radius: 0.25rem;
  font-family: 'Courier New', monospace;
}

.ProseMirror pre {
  background-color: #f3f4f6;
  padding: 1rem;
  border-radius: 0.5rem;
  overflow-x: auto;
}

.ProseMirror pre code {
  background-color: transparent;
  padding: 0;
}
</style>

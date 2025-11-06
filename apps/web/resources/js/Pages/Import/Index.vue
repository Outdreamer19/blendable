<template>
  <AppLayout>
    <template #header>
      <div class="flex items-center">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
          Import Conversations
        </h2>
      </div>
    </template>

    <div class="py-12">
      <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
          <div class="p-6">
            <div class="mb-8">
              <h3 class="text-lg font-medium text-gray-900 mb-2">Import from ChatGPT or Claude</h3>
              <p class="text-gray-600">
                Upload JSON export files from ChatGPT or Claude to import your conversations into Blendable.
              </p>
            </div>

            <!-- Import Forms -->
            <div class="grid grid-cols-1 gap-8 lg:grid-cols-2">
              <!-- ChatGPT Import -->
              <div class="border border-gray-200 rounded-lg p-6">
                <div class="flex items-center mb-4">
                  <div class="h-10 w-10 bg-green-100 rounded-lg flex items-center justify-center mr-3">
                    <span class="text-green-600 font-semibold text-lg">🤖</span>
                  </div>
                  <div>
                    <h4 class="text-lg font-semibold text-gray-900">ChatGPT</h4>
                    <p class="text-sm text-gray-500">Import ChatGPT conversations</p>
                  </div>
                </div>

                <form @submit.prevent="importChatGPT" class="space-y-4">
                  <div>
                    <label for="chatgpt-workspace" class="block text-sm font-medium text-gray-700 mb-2">
                      Workspace
                    </label>
                    <select
                      id="chatgpt-workspace"
                      v-model="chatGPTForm.workspace_id"
                      class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                      :class="{ 'border-red-300': errors['chatgpt.workspace_id'] }"
                      required
                    >
                      <option value="">Select a workspace</option>
                      <option
                        v-for="workspace in workspaces"
                        :key="workspace.id"
                        :value="workspace.id"
                      >
                        {{ workspace.name }}
                      </option>
                    </select>
                    <div v-if="errors['chatgpt.workspace_id']" class="mt-1 text-sm text-red-600">
                      {{ errors['chatgpt.workspace_id'] }}
                    </div>
                  </div>

                  <div>
                    <label for="chatgpt-file" class="block text-sm font-medium text-gray-700 mb-2">
                      JSON File
                    </label>
                    <input
                      id="chatgpt-file"
                      ref="chatGPTFile"
                      type="file"
                      accept=".json"
                      class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                      :class="{ 'border-red-300': errors['chatgpt.file'] }"
                      required
                    />
                    <div v-if="errors['chatgpt.file']" class="mt-1 text-sm text-red-600">
                      {{ errors['chatgpt.file'] }}
                    </div>
                    <p class="mt-1 text-sm text-gray-500">
                      Upload your ChatGPT export file (JSON format, max 10MB)
                    </p>
                  </div>

                  <button
                    type="submit"
                    :disabled="chatGPTProcessing"
                    class="w-full bg-green-600 text-white py-2 px-4 rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 disabled:opacity-50"
                  >
                    {{ chatGPTProcessing ? 'Importing...' : 'Import ChatGPT' }}
                  </button>
                </form>
              </div>

              <!-- Claude Import -->
              <div class="border border-gray-200 rounded-lg p-6">
                <div class="flex items-center mb-4">
                  <div class="h-10 w-10 bg-orange-100 rounded-lg flex items-center justify-center mr-3">
                    <span class="text-orange-600 font-semibold text-lg">🧠</span>
                  </div>
                  <div>
                    <h4 class="text-lg font-semibold text-gray-900">Claude</h4>
                    <p class="text-sm text-gray-500">Import Claude conversations</p>
                  </div>
                </div>

                <form @submit.prevent="importClaude" class="space-y-4">
                  <div>
                    <label for="claude-workspace" class="block text-sm font-medium text-gray-700 mb-2">
                      Workspace
                    </label>
                    <select
                      id="claude-workspace"
                      v-model="claudeForm.workspace_id"
                      class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                      :class="{ 'border-red-300': errors['claude.workspace_id'] }"
                      required
                    >
                      <option value="">Select a workspace</option>
                      <option
                        v-for="workspace in workspaces"
                        :key="workspace.id"
                        :value="workspace.id"
                      >
                        {{ workspace.name }}
                      </option>
                    </select>
                    <div v-if="errors['claude.workspace_id']" class="mt-1 text-sm text-red-600">
                      {{ errors['claude.workspace_id'] }}
                    </div>
                  </div>

                  <div>
                    <label for="claude-file" class="block text-sm font-medium text-gray-700 mb-2">
                      JSON File
                    </label>
                    <input
                      id="claude-file"
                      ref="claudeFile"
                      type="file"
                      accept=".json"
                      class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                      :class="{ 'border-red-300': errors['claude.file'] }"
                      required
                    />
                    <div v-if="errors['claude.file']" class="mt-1 text-sm text-red-600">
                      {{ errors['claude.file'] }}
                    </div>
                    <p class="mt-1 text-sm text-gray-500">
                      Upload your Claude export file (JSON format, max 10MB)
                    </p>
                  </div>

                  <button
                    type="submit"
                    :disabled="claudeProcessing"
                    class="w-full bg-orange-600 text-white py-2 px-4 rounded-md hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 disabled:opacity-50"
                  >
                    {{ claudeProcessing ? 'Importing...' : 'Import Claude' }}
                  </button>
                </form>
              </div>
            </div>

            <!-- Help Section -->
            <div class="mt-12 border-t border-gray-200 pt-8">
              <h3 class="text-lg font-medium text-gray-900 mb-4">How to Export from ChatGPT/Claude</h3>
              <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
                <div>
                  <h4 class="font-medium text-gray-900 mb-2">ChatGPT</h4>
                  <ol class="text-sm text-gray-600 space-y-1 list-decimal list-inside">
                    <li>Go to your ChatGPT conversation</li>
                    <li>Click the "Share" button</li>
                    <li>Select "Export" and choose JSON format</li>
                    <li>Download the file and upload it here</li>
                  </ol>
                </div>
                <div>
                  <h4 class="font-medium text-gray-900 mb-2">Claude</h4>
                  <ol class="text-sm text-gray-600 space-y-1 list-decimal list-inside">
                    <li>Go to your Claude conversation</li>
                    <li>Click the "Share" button</li>
                    <li>Select "Export" and choose JSON format</li>
                    <li>Download the file and upload it here</li>
                  </ol>
                </div>
              </div>
            </div>

            <!-- JSON Format Example -->
            <div class="mt-8 border-t border-gray-200 pt-8">
              <h3 class="text-lg font-medium text-gray-900 mb-4">Expected JSON Format</h3>
              <div class="bg-gray-50 rounded-lg p-4">
                <pre class="text-sm text-gray-600 overflow-auto"><code>{
  "title": "Sample Conversation",
  "messages": [
    {
      "role": "user",
      "content": "Hello, how are you?",
      "created_at": "2024-01-01T12:00:00Z"
    },
    {
      "role": "assistant",
      "content": "I'm doing well, thank you!",
      "created_at": "2024-01-01T12:00:30Z"
    }
  ]
}</code></pre>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import { router } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'

interface Workspace {
  id: number
  name: string
}

interface Props {
  workspaces: Workspace[]
  errors?: Record<string, string>
}

const props = defineProps<Props>()

const chatGPTForm = ref({
  workspace_id: '',
})

const claudeForm = ref({
  workspace_id: '',
})

const chatGPTFile = ref<HTMLInputElement>()
const claudeFile = ref<HTMLInputElement>()

const chatGPTProcessing = ref(false)
const claudeProcessing = ref(false)

const errors = ref<Record<string, string>>(props.errors || {})

const importChatGPT = async () => {
  if (!chatGPTFile.value?.files?.[0]) {
    errors.value['chatgpt.file'] = 'Please select a file'
    return
  }

  chatGPTProcessing.value = true
  errors.value = {}

  const formData = new FormData()
  formData.append('file', chatGPTFile.value.files[0])
  formData.append('workspace_id', chatGPTForm.value.workspace_id)

  try {
    await router.post(route('import.chatgpt'), formData, {
      forceFormData: true,
      onError: (errs) => {
        errors.value = errs
      },
    })
  } finally {
    chatGPTProcessing.value = false
  }
}

const importClaude = async () => {
  if (!claudeFile.value?.files?.[0]) {
    errors.value['claude.file'] = 'Please select a file'
    return
  }

  claudeProcessing.value = true
  errors.value = {}

  const formData = new FormData()
  formData.append('file', claudeFile.value.files[0])
  formData.append('workspace_id', claudeForm.value.workspace_id)

  try {
    await router.post(route('import.claude'), formData, {
      forceFormData: true,
      onError: (errs) => {
        errors.value = errs
      },
    })
  } finally {
    claudeProcessing.value = false
  }
}
</script>

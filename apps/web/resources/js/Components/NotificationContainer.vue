<template>
  <div class="fixed top-4 right-4 z-50 space-y-2 max-w-sm w-full">
    <ErrorNotification
      v-for="notification in notifications"
      :key="notification.id"
      :type="notification.type"
      :title="notification.title"
      :message="notification.message"
      :details="notification.details"
      :duration="notification.duration"
      @close="removeNotification(notification.id)"
    />
  </div>
</template>

<script setup lang="ts">
import { storeToRefs } from 'pinia'
import { useErrorStore } from '@/stores/errorStore'
import ErrorNotification from './ErrorNotification.vue'

const errorStore = useErrorStore()
const { notifications } = storeToRefs(errorStore)
const { removeNotification } = errorStore
</script>

import { defineStore } from 'pinia'
import { ref } from 'vue'

export interface ErrorNotification {
  id: string
  type: 'error' | 'warning' | 'success' | 'info'
  title: string
  message: string
  details?: string
  duration?: number
}

export const useErrorStore = defineStore('error', () => {
  const notifications = ref<ErrorNotification[]>([])

  const addNotification = (notification: Omit<ErrorNotification, 'id'>) => {
    const id = Math.random().toString(36).substr(2, 9)
    notifications.value.push({
      id,
      ...notification,
    })
  }

  const removeNotification = (id: string) => {
    const index = notifications.value.findIndex(n => n.id === id)
    if (index > -1) {
      notifications.value.splice(index, 1)
    }
  }

  const clearAll = () => {
    notifications.value = []
  }

  // Convenience methods
  const showError = (title: string, message: string, details?: string) => {
    addNotification({
      type: 'error',
      title,
      message,
      details,
      duration: 8000,
    })
  }

  const showWarning = (title: string, message: string, details?: string) => {
    addNotification({
      type: 'warning',
      title,
      message,
      details,
      duration: 6000,
    })
  }

  const showSuccess = (title: string, message: string, details?: string) => {
    addNotification({
      type: 'success',
      title,
      message,
      details,
      duration: 4000,
    })
  }

  const showInfo = (title: string, message: string, details?: string) => {
    addNotification({
      type: 'info',
      title,
      message,
      details,
      duration: 5000,
    })
  }

  // Handle API errors
  const handleApiError = (error: any) => {
    console.error('API Error:', error)

    if (error.response) {
      const { status, data } = error.response
      
      switch (status) {
        case 400:
          showError('Bad Request', data.message || 'Invalid request data')
          break
        case 401:
          showError('Unauthorized', 'Please log in to continue')
          break
        case 403:
          showError('Forbidden', 'You do not have permission to perform this action')
          break
        case 404:
          showError('Not Found', 'The requested resource was not found')
          break
        case 422:
          if (data.errors) {
            // Handle validation errors
            Object.entries(data.errors).forEach(([field, messages]: [string, any]) => {
              showError('Validation Error', `${field}: ${messages[0]}`)
            })
          } else {
            showError('Validation Error', data.message || 'Please check your input')
          }
          break
        case 429:
          showWarning('Rate Limited', 'Too many requests. Please try again later.')
          break
        case 500:
          showError('Server Error', 'An internal server error occurred. Please try again later.')
          break
        default:
          showError('Request Failed', data.message || `Request failed with status ${status}`)
      }
    } else if (error.request) {
      showError('Network Error', 'Unable to connect to the server. Please check your internet connection.')
    } else {
      showError('Unexpected Error', error.message || 'An unexpected error occurred')
    }
  }

  return {
    notifications,
    addNotification,
    removeNotification,
    clearAll,
    showError,
    showWarning,
    showSuccess,
    showInfo,
    handleApiError,
  }
})

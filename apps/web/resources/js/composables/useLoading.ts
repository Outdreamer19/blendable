import { ref, computed } from 'vue'

export function useLoading(initialState = false) {
  const isLoading = ref(initialState)
  const loadingStates = ref<Record<string, boolean>>({})

  const setLoading = (state: boolean, key?: string) => {
    if (key) {
      loadingStates.value[key] = state
    } else {
      isLoading.value = state
    }
  }

  const startLoading = (key?: string) => {
    setLoading(true, key)
  }

  const stopLoading = (key?: string) => {
    setLoading(false, key)
  }

  const withLoading = async <T>(
    asyncFn: () => Promise<T>,
    key?: string
  ): Promise<T> => {
    try {
      startLoading(key)
      return await asyncFn()
    } finally {
      stopLoading(key)
    }
  }

  const isAnyLoading = computed(() => {
    return isLoading.value || Object.values(loadingStates.value).some(Boolean)
  })

  const getLoadingState = (key: string) => {
    return computed(() => loadingStates.value[key] || false)
  }

  return {
    isLoading: computed(() => isLoading.value),
    loadingStates: computed(() => loadingStates.value),
    isAnyLoading,
    setLoading,
    startLoading,
    stopLoading,
    withLoading,
    getLoadingState,
  }
}

export function useAsyncOperation<T>() {
  const { isLoading, withLoading } = useLoading()
  const error = ref<string | null>(null)
  const data = ref<T | null>(null)

  const execute = async (asyncFn: () => Promise<T>): Promise<T | null> => {
    try {
      error.value = null
      data.value = await withLoading(asyncFn)
      return data.value
    } catch (err: any) {
      error.value = err.message || 'An error occurred'
      return null
    }
  }

  const reset = () => {
    error.value = null
    data.value = null
  }

  return {
    isLoading,
    error: computed(() => error.value),
    data: computed(() => data.value),
    execute,
    reset,
  }
}

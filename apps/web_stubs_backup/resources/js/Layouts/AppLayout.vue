<template>
  <div class="min-h-screen flex">
    <aside class="w-64 border-r p-4 space-y-2">
      <h1 class="font-bold">OmniAI</h1>
      <nav class="space-y-1">
        <a href="/app/chats/1">Chats</a>
        <a href="/app/personas">Personas</a>
        <a href="/app/prompts">Prompts</a>
        <a href="/app/images">Images</a>
        <a href="/app/usage">Usage</a>
        <a href="/app/billing">Billing</a>
      </nav>
    </aside>
    <main class="flex-1">
      <slot />
    </main>
  </div>
</template>

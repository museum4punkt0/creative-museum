<template>
  <div>
    <h1 class="text-primary p-7 text-2xl font-bold">Login</h1>
  </div>
</template>

<script setup>
  const nuxtApp = useNuxtApp()
  if (process.client) {
    nuxtApp.$auth.login()
  }
</script>
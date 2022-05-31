<template>
  <div>
    <h1 class="text-primary p-7 text-2xl font-bold">Login</h1>
  </div>
</template>

<script setup lang="ts">
  const ctx = useNuxtApp()
  ctx.$auth.loginWith('oauth2')
</script>
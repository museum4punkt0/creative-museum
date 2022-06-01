<template>
  <div>
    <h1 class="text-primary p-7 text-2xl font-bold">Login callback</h1>
    <NuxtLink to="/sample">Sample</NuxtLink>
  </div>
</template>
<script setup>

  const nuxtApp = useNuxtApp()

  definePageMeta({
      middleware: ['auth']
  })

</script>
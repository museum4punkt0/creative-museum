<template>
  <div>
    <h1 class="text-primary p-7 text-2xl font-bold">Login callback</h1>
    <nuxt-link to="/sample">Sample</nuxt-link>
  </div>
</template>

<script>
export default {
  middleware: 'auth'
}
</script>

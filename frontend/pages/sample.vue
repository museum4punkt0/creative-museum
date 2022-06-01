<template>
  <div>
    <h1 class="text-primary p-7 text-2xl font-bold">Sample site No1</h1>
    <NuxtLink to="/login">Login</NuxtLink>
    <button @click.prevent="logout">Logout</button>
    <NuxtLink to="/sample2">Sample 2</NuxtLink>
  </div>
</template>
<script setup>
const nuxtApp = useNuxtApp()

function logout() {
  nuxtApp.$auth.logout()
}

</script>
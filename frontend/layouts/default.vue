<template>
  <div class="bg-black h-screen flex flex-col page">
    <div class="flex-1 overflow-y-auto">
      <Logo class="text-white/30 h-12 m-5" />
      <slot />
    </div>
  </div>
</template>
<script lang="ts" setup>
import Logo from '@/assets/images/logo.svg'
</script>
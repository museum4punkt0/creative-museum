<template>
  <div class="bg-grey h-screen flex flex-col page">
    <div class="flex-1 overflow-y-auto">
      <div class="container">
        <div class="flex flex-row justify-between align-middle items-center">
          <Logo class="text-white/50 h-12 m-5" />
          <button class="relative block rounded-full w-8 h-8 border border-white after:(block content-[''] w-4 h-px bg-white absolute top-1/2 transform-y-px left-1/2 -transform-x-1/2)" />
          <div class="">
            Profile
          </div>
        </div>
        <slot />
      </div>
    </div>
  </div>
</template>
<script lang="ts" setup>
import Logo from '@/assets/images/logo.svg'
</script>
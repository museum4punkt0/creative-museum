<template>
  <div tabindex="0">
    <OverlaysLogin v-if="!$auth.loggedIn" />
    <OverlaysTutorial />
    <OverlaysProfileUpdate v-if="$auth.loggedIn" />
    <OverlaysNotifications v-if="$auth.loggedIn" />
    <transition
      enter-active-class="duration-300 ease-out opacity-0"
      enter-to-class="opacity-100"
      leave-active-class="duration-200 ease-in"
      leave-class="opacity-100"
      leave-to-class="opacity-0"
    >
      <OverlaysSplashscreen v-if="!splashscreenShown" />
    </transition>
    <OverlaysCampaignClosed v-if="$auth.loggedIn" />
  </div>
</template>
<script>
import { defineComponent, computed, useStore } from '@nuxtjs/composition-api'

export default defineComponent({
  setup() {
    const store = useStore()

    const splashscreenShown = computed(() => {
      return store.getters.splashscreenShown
    })

    if (!splashscreenShown.value) {
      setTimeout(() => {
        store.dispatch('splashscreenShown')
      }, 1000)
    }

    return {
      splashscreenShown,
    }
  },
})
</script>

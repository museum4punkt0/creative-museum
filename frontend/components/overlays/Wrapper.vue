<template>
  <div>
    <OverlaysLogin v-if="!$auth.loggedIn" />
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
  </div>
</template>
<script>
import { defineComponent, computed, useStore} from '@nuxtjs/composition-api'

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
      splashscreenShown
    }
  },
})
</script>

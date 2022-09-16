<template>
  <UtilitiesSlideUp v-if="showLogin" :closable="true" @closeModal="showLogin = false">
    <div class="flex flex-col flex-1 h-full justify-between">
      <div class="page-header px-6">
        <a class="back-btn" @click.prevent="showLogin = false">
        {{ $t('globalLogin.header') }}</a>
      </div>
      <div class="p-6">
        <p class="mb-6">
          {{ $t('globalLogin.text') }}
        </p>
        <button class="btn-highlight w-full" @click.prevent="$auth.login()">{{ $t('globalLogin.button') }}</button>
      </div>
    </div>
  </UtilitiesSlideUp>
</template>
<script>
import { defineComponent, useStore, computed } from '@nuxtjs/composition-api'

export default defineComponent({
  setup() {
    const store = useStore()
    const showLogin = computed({
      get() { return store.state.showLogin },
      set() {
        store.dispatch('hideLogin')
      }
    })
    return {
      showLogin,
    }
  },
})
</script>

<template>
  <UtilitiesModal v-if="tutorialOpen" :aria-label="$t('modal.tutorial')">
    <Tutorial @closeModal="closeTutorial" />
  </UtilitiesModal>
</template>
<script>
import {
  defineComponent,
  ref,
  useContext,
  useStore,
  watch,
} from '@nuxtjs/composition-api'

export default defineComponent({
  setup() {
    const tutorialOpen = ref(false)
    const store = useStore()
    const { $auth } = useContext()

    if ($auth.loggedIn && !$auth.user.tutorial && $auth.user.username) {
      tutorialOpen.value = true
    }

    $auth.$storage.watchState('user.username', (_) => {
      tutorialOpen.value = true
    })

    watch(
      () => store.getters.showTutorial,
      function (newVal) {
        tutorialOpen.value = newVal
      }
    )

    function closeTutorial() {
      tutorialOpen.value = false
      store.dispatch('hideTutorial')
    }

    return {
      tutorialOpen,
      closeTutorial,
    }
  },
})
</script>

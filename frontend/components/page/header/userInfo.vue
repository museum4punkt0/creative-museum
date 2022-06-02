<template>
  <div w:flex="~ row" w:space="md:x-4" w:align="items-center">
    <img
      src="/images/placeholder_profile.png"
      w:w="6"
      w:h="6"
      w:object="contain center"
      w:rounded="full"
    />
    <span
      w:text="sm overflow-ellipsis ..."
      w:display="hidden md:inline-block"
      w:overflow="hidden"
      w:min-w="24"
      w:max-w="32"
    >
      @Profilname
    </span>
  </div>
</template>
<script>
import { useStore, computed, defineComponent } from '@nuxtjs/composition-api'

export default defineComponent({
  setup() {
    const store = useStore()

    return {
      user: computed(() => store.state.auth.user),
    }
  },
})
</script>

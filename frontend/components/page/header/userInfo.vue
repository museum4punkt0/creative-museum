<template>
  <div
      v-if="user" w:flex="~ row" w:space="md:x-4" w:align="items-center">
    <div
      w:position="relative"
    >
      <img
        src="/images/placeholder_profile.png"
        w:w="6"
        w:h="6"
        w:object="contain center"
        w:rounded="full"
      />
      <span
        w:pos="absolute"
        w:h="2"
        w:w="2"
        w:top="0"
        w:right="0"
        w:rounded="full"
        w:bg="color1"
        w:white
      />
      <span
        w:pos="absolute"
        w:top="1"
        w:p="y-0.5 x-2"
        w:m="-r-1"
        w:right="full"
        w:rounded="xl"
        w:bg="color1"
        w:text="xs black space-nowrap"
      >{{ user.score ? user.score : 0 }} P</span>
    </div>
    <span
      w:text="sm overflow-ellipsis ..."
      w:display="hidden md:inline-block"
      w:overflow="hidden"
      w:min-w="24"
      w:max-w="32"
    >
      @{{ user.username }}
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

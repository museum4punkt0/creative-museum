<template>
  <div class="bg-grey h-screen flex flex-col page">
    <div class="flex-1 overflow-y-auto" w:overflow="x-hidden">
      <div
        w:bg="grey"
        w:shadow="lg black/20"
        w:pos="sticky"
        w:z="10"
        w:top="0"
      >
        <PageHeader />
      </div>
      <div w:container="~" w:p="6 lg:t-20">
        <Nuxt />
      </div>
    </div>
    <SlideUp v-if="showLogin" variant="bottom">
      LOGIN!
    </SlideUp>
  </div>
</template>
<script>
import { defineComponent, computed, useStore } from '@nuxtjs/composition-api'

export default defineComponent({
  name: 'LayoutDefault',
  setup() {

    const store = useStore()
    const showLogin = computed(() => store.state.showLogin);

    return {
      showLogin
    }

  }
})
</script>

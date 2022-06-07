<template>
  <div></div>
</template>
<script>
import { defineComponent, computed, useStore, useRouter } from '@nuxtjs/composition-api'

export default defineComponent({
  name: 'IndexPage',
  setup() {
    const store = useStore()
    const user = computed(() => store.state.auth.user);
    const router = useRouter()

    if (user.value.tutorial === false) {
      router.push('/tutorial')
    } else {
      router.push('/campaigns')
    }

  }
})
</script>
<template>
  <div>

  </div>
</template>
<script>
import {defineComponent, useRouter} from '@nuxtjs/composition-api'

export default defineComponent({
  middleware: 'auth',
  setup() {
    const router = useRouter();
    router.push('/')
  },
})
</script>

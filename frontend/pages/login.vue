<template>
  <div>
    <h1 class="text-2xl font-bold p-7">Login</h1>
  </div>
</template>
<script>
import {defineComponent, useContext, useStore} from '@nuxtjs/composition-api'

export default defineComponent({
  setup() {
    const { $auth } = useContext()
    $auth.login()
  },
})
</script>

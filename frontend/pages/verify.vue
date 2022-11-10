<template>
  <div>
    LoggedIn!
  </div>
</template>
<script>
import {defineComponent} from '@nuxtjs/composition-api'

export default defineComponent({
  middleware: ['auth']
})
</script>

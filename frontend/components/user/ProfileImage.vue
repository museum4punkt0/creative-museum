<template>
  <div class="highlight-bg rounded-full w-8 h-8">
    <img
      v-if="'profilePicture' in user"
      :src="backendUrl + user.profilePicture.contentUrl"
      class="w-8 h-8 rounded-full"
    />
  </div>
</template>
<script>
import { defineComponent, useContext } from '@nuxtjs/composition-api'

export default defineComponent({
  props: {
    user: {
      type: Object,
      default: () => {},
    },
  },
  setup() {
    const { $config } = useContext()

    return {
      backendUrl: $config.backendUrl
    }
  }
})
</script>

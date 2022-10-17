<template>
  <div class="highlight-bg rounded-full w-9 h-9">
    <img
      v-if="'profilePicture' in user"
      :src="backendURL + user.profilePicture.contentUrl"
      class="w-9 h-9 object-cover object-center rounded-full"
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
      backendURL: $config.backendURL,
    }
  },
})
</script>

<template>
  <div class="highlight-bg rounded-full w-8 h-8">
    <img
      v-if="'profilePicture' in user"
      :src="
        'https://backend.creative-museum.ddev.site' +
        user.profilePicture.contentUrl
      "
      class="w-8 h-8 rounded-full"
    />
  </div>
</template>
<script>
import { defineComponent } from '@nuxtjs/composition-api'

export default defineComponent({
  props: {
    user: {
      type: Object,
      default: () => {},
    },
  },
})
</script>

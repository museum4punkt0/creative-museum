<template>
  <div class="highlight-bg" w:rounded="full" w:w="8" w:h="8">
    <img
      v-if="'profilePicture' in user"
      :src="
        'https://backend.creative-museum.ddev.site' +
        user.profilePicture.contentUrl
      "
      w:w="8"
      w:h="8"
      w:rounded="full"
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

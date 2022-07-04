<template>
  <div>
    <div
      w:flex="row"
    >
      <UserProfileImage :user="post.author" />
      {{ post.author }}
    </div>
    <div>...</div>
  </div>
</template>
<script>
import { defineComponent } from '@nuxtjs/composition-api'

export default defineComponent({
  props: {
    post: {
      type: Object,
      required: true
    }
  },
  setup() {

  },
})
</script>

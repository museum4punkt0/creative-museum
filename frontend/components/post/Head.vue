<template>
  <div
    w:flex="~ row"
    w:justify="between"
  >
    <NuxtLink
      :to="`user/${post.author.id}`"
      w:flex="~ row"
    >
      <UserProfileImage :user="post.author" />
      <div w:flex="~ col">
        <span w:text="lg">@{{ post.author.username }}</span>
        <span class="highlight-text" w:text="sm" w:m="t-1">{{ $dayjs(post.created).fromNow() }}</span>
      </div>
    </NuxtLink>
    <div>
      <ThreeDots />
    </div>
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

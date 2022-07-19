<template>
  <div
    v-if="post.author"
    w:flex="~ row"
    w:justify="between"
  >
    <NuxtLink
      :to="localePath(`/user/${post.author.username}`)"
      w:flex="~ row"
    >
      <UserProfileImage :user="post.author" w:m="r-4" />
      <div w:flex="~ col">
        <span w:text="lg">{{ post.author.username }}</span>
        <span class="highlight-text" w:text="sm" w:m="t-1">{{ $dayjs.duration($dayjs().diff($dayjs(post.created))).days() > 2 ? $dayjs(post.created).format('DD.MM.YYYY') : $dayjs(post.created).fromNow() }}</span>
      </div>
    </NuxtLink>
    <div @click="showAdditionalOptions = !showAdditionalOptions">
      <ThreeDots w:cursor="pointer"  />
    </div>
    <transition
      enter-active-class="duration-300 ease-out -bottom-full lg:opacity-0 lg:bottom-auto"
      enter-to-class="bottom-0 lg:bottom-auto lg:opacity-100"
      leave-active-class="duration-200 ease-in"
      leave-class="bottom-0 lg:bottom-auto lg:top-1/2 lg:opacity-100"
      leave-to-class="bottom-full lg:bottom-auto lg:opacity-0"
    >
      <SlideUp v-show="showAdditionalOptions" :closable="true" @closeSlideUp="showAdditionalOptions = false">
        <div w:p="6">
          3 Punkte Menü
        </div>
      </SlideUp>
    </transition>
  </div>
</template>
<script>
import { defineComponent, ref } from '@nuxtjs/composition-api'

export default defineComponent({
    props: {
      post: {
        type: Object,
        required: true
      }
    },
    setup() {
      const showAdditionalOptions = ref(false)

      return {
        showAdditionalOptions
      }
    }
})
</script>

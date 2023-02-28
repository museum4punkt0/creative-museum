<template>
  <div v-if="filteredPosts">

    <div
      v-for="(post, index) in filteredPosts"
      v-show="showItem === index"
      :key="index"
      ref="items"
      class="absolute l-0 t-0 r-0 b-0 w-full"
      :class="`z-${90 - index}`"
    >
      <CampaignResult v-if="post.type === 'result'" :campaign-title="campaign.title" :campaign-result="filteredPosts[0].post" :campaign-color="campaign.color" :campaign-closed="campaign.stop" class="mt-0" />
      <KioskPostItem
        v-else
        :post="post"
      />
    </div>
  </div>
</template>
<script>
import {
  defineComponent,
  ref,
  onMounted,
  watch,
  toRef,
  computed
} from '@nuxtjs/composition-api'

export default defineComponent({
  props: {
    posts: {
      type: Array,
      required: true,
    },
    campaign: {
      type: Object,
      required: true
    },
    timeout: {
      type: Number,
      default: 5000
    },
    progress: {
      type: Number,
      default: 0
    },
    source: {
      type: String,
      default: '',
    },
  },
  setup(props, context) {
    const items = ref(null)
    const showItem = ref(0)
    const progressRef = toRef(props, 'progress')

    watch(progressRef, (newProgress) => {
      if (newProgress === 0) {
        animateItems()
      }
    })

    const filteredPosts = computed(() => {
      const items = []
      if (props.posts[0] && props.posts[0].length) {
        items[0] = {
          type: 'result',
          post: props.posts[0]
        }
      }
      props.posts[1].forEach((item) => {
        if (item.type !== 'system') {
          items.push(item)
        }
      })
      return items

    })

    function animateItems() {
      showItem.value = 0

      if (items.value && items.value.length > 1) {

        context.emit('updateProgress', { progress : ( 1 / items.value.length) * 100 })
        context.emit('initItems', { itemCount: items.value.length })

        items.value.forEach((_, index) => {
          setTimeout(() => {
            showItem.value = index + 1
            context.emit('initItems', { itemCount: items.value.length })
            context.emit('updateProgress', { progress : ((index + 2) / items.value.length) * 100 })
          }, props.timeout * (index + 1))
        })
      }
    }

    onMounted(() => {
      animateItems()
    })

    return {
      items,
      progressRef,
      showItem,
      filteredPosts,
      animateItems
    }

  }
})
</script>

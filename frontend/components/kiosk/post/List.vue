<template>
  <div v-if="filteredPosts">
    <div
      v-if="posts[0] && posts[0].length > 0"
      v-show="showItem === 0"
      ref="items"
      class="absolute l-0 t-0 r-0 b-0 w-full z-100"
    >
      <CampaignResult :campaign-title="campaign.title" :campaign-result="posts[0]" :campaign-color="campaign.color" :campaign-closed="campaign.stop" class="mt-0" />
    </div>
    <div
      v-for="(post, index) in filteredPosts"
      v-show="posts[0] && posts[0].length > 0 ? showItem === index + 1 : showItem === index"
      :key="index"
      ref="items"
      class="absolute l-0 t-0 r-0 b-0 w-full"
      :class="`z-${90 - index}`"
    >
      <KioskPostItem
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
      default: 1000
    },
    progress: {
      type: Number,
      default: 1000
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
    let stepDuration = 0

    watch(progressRef, (newProgress) => {
      if (newProgress === 0) {
        animateItems()
      }
    })

    const filteredPosts = computed(() => {
      return props.posts[1].filter((item) => {
        return item.type !== 'system'
      })
    })

    function animateItems() {
      showItem.value = 0
      const itemCount = items.value.length
      if (items && itemCount > 1) {

        stepDuration = (itemCount) * props.timeout
        context.emit('updateDuration', { duration: stepDuration, itemCount })

        items.value.forEach((_, index) => {
          setTimeout(function(){
            showItem.value = index + 1
            context.emit('updateProgress', { progress : ((index + 1) / itemCount) * 100 })
          }, props.timeout * (index + 1))

        })
      } else {
        setTimeout(function(){
          animateItems()
        }, 1000)
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
      stepDuration,
      animateItems
    }

  }
})
</script>

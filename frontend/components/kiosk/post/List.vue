<template>
  <div>
    <div
      v-for="(post, index) in filteredPosts"
      :key="index"
    >
        <div
          v-if="index === 0 && posts[0] && posts[0].length > 0"
          v-show="showItem === 0"
          ref="items"
          class="absolute l-0 t-0 r-0 b-0 w-full z-100"
        >
          <transition name="fade">
            <CampaignResult :campaign-title="campaign.title" :campaign-result="posts[0]" :campaign-color="campaign.color" :campaign-closed="campaign.stop" class="mt-0" />
          </transition>
        </div>
        <div
          v-show="posts[0] && posts[0].length > 0 ? showItem === index + 1 : showItem === index"
          ref="items"
          class="absolute l-0 t-0 r-0 b-0 w-full"
          :class="`z-${99 - index}`"
        >
          <KioskPostItem
            :post="post"
          />
        </div>
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

      if (items && items.value.length > 1) {
        items.value.forEach((item, index) => {
          setTimeout(function(){
            showItem.value = index + 1
            context.emit('updateProgress', { progress : ((index + 1) / itemCount) * 100 })
          }, props.timeout * (index + 1))

        })
      }
    }

    onMounted(() => {
      context.emit('updateProgress', { progress : 0 })
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

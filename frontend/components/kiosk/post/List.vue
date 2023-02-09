<template>
  <div>
    <div
      v-for="(post, index) in posts[1]"
      :key="post.id"
      >
      <div
        v-if="index === 0 && posts[0] && posts[0].length > 0"
        ref="item"
        class="absolute l-0 t-0 r-0 b-0 w-full z-100 bg-grey"
      >
        <CampaignResult :campaign-title="campaign.title" :campaign-result="posts[0]" :campaign-color="campaign.color" :campaign-closed="campaign.stop"/>
      </div>
      <div
        ref="item"
        class="absolute l-0 t-0 r-0 b-0 w-full bg-grey"
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
  defineComponent
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
    source: {
      type: String,
      default: '',
    },
  }
})
</script>

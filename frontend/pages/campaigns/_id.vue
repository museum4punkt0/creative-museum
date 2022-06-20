<template>
  <div>
    <div v-if="campaign">
      <CampaignHead :campaign="campaign" />
      <div v-if="posts">
        <PostItem
          v-for="(post, key) in posts"
          :key="key"
          :post="post"
        />
      </div>
      <div v-else>
        No Posts
      </div>
    </div>
    <div v-else>
      No Campaign found
    </div>

  </div>
</template>

<script>

import { defineComponent, useAsync, useRoute } from '@nuxtjs/composition-api'
import { campaignApi } from '@/api/campaign'
import { postApi } from '@/api/post'

export default defineComponent({
  name: 'CampaignPage',
  setup() {

    const route = useRoute()

    const { fetchCampaign } = campaignApi()
    const { fetchPostsByCampaign } = postApi()

    let campaign = null
    let posts = null

    if (route.value.params.id) {
      campaign = useAsync(() => fetchCampaign(route.value.params.id), `campaign-${route.value.params.id}`)
      posts = useAsync(() => fetchPostsByCampaign(route.value.params.id), `posts-${route.value.params.id}`)
    }

    return {
      campaign,
      posts
    }

  },
})
</script>
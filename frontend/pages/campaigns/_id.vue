<template>
  <div>
    <div w:grid="~ cols-1 lg:cols-12 lg:gap-4">
      <div v-if="isLargerThanLg" w:grid="col-span-3" w:pr="10">
        <div
          w:mb="10"
        >
          <div
            w:w="21"
            w:h="21"
            w:rounded="full"
            w:mb="4"
            class="highlight-bg"
          ></div>
          <p
            w:text="2xl"
          >
            Max Muster
          </p>
          <p
            w:text="lg"
            w:mb="3"
            class="highlight-text"
          >
            Lorem Ipsum
          </p>
          <p>
            Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua.
          </p>
          <button
            w:mt="10"
            w:py="2"
            w:w="full"
            class="btn-outline"
            >
            Profil bearbeiten
          </button>
        </div>
        <div
          w:mb="10"
        >
          <p
            w:font="bold"
            w:mb="3"
          >
            Punktestand
          </p>
          <div
            w:px="4"
            w:py="2"
            w:rounded="full"
            w:flex="~ row"
            w:align="items-end"
            w:justify="center"
            class="box-shadow"
          >
            <span w:text="2xl" w:mr="2">11</span>
            <span>Punkte</span>
          </div>
        </div>
      </div>
      <div w:grid="lg:col-span-6">
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
      <div v-if="isLargerThanLg" w:grid="col-span-3">
        Sidebar Right
      </div>
    </div>
  </div>
</template>

<script>

import { defineComponent, useAsync, useRoute, computed, useContext, ref } from '@nuxtjs/composition-api'
import { campaignApi } from '@/api/campaign'
import { postApi } from '@/api/post'
import { on } from 'events'

export default defineComponent({
  name: 'CampaignPage',
  setup() {

    const route = useRoute()
    const context = useContext()

    const postComment = ref(false)

    const { fetchCampaign } = campaignApi()
    const { fetchPostsByCampaign } = postApi()

    const isLargerThanLg = computed(() => {
      return context.$breakpoints.lLg
    })

    let campaign = null
    let posts = null

    if (route.value.params.id) {
      campaign = useAsync(() => fetchCampaign(route.value.params.id), `campaign-${route.value.params.id}`)
      posts = useAsync(() => fetchPostsByCampaign(route.value.params.id), `posts-${route.value.params.id}`)
    }

    return {
      postComment,
      campaign,
      posts,
      isLargerThanLg
    }
  }
})
</script>

<template>
  <div>
    <div w:grid="~ cols-1 lg:cols-12 lg:gap-4">
      <div w:grid="col-span-3" w:pr="10">
        <div v-if="isLargerThanLg">
          <CampaignSidebarUserInfo :campaign="campaign" />
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
      <div w:grid="col-span-3" w:pl="10">
        <div v-if="isLargerThanLg">
          <div
            w:mb="10"
          >
            <p
              w:text="2xl"
            >
              {{ $t('campaign.latestPosts') }}
            </p>
          </div>
          <div
            w:mb="10"
          >
            <p
              w:text="lg"
            >
              Heute
            </p>
            <div
              w:flex="~ row"
              w:mt="2"
            >
              <div
              w:w="10"
              w:h="10"
              w:rounded="full"
              w:mb="4"
              w:mr="3"
              w:overflow="hidden"
              w:flex="shrink-0"
              class="highlight-bg"
              >
                <img src="https://fakeimg.pl/40/" alt="Dummy Image" w:max-w="10" w:h="auto" />
              </div>
              <div
                w:flex="~ col grow"
              >
                <p w:mb="1">Lorem Ipsum sit dolor</p>
                <p w:text="sm" class="highlight-text">Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet.</p>
              </div>
            </div>
          </div>
          <div
            w:mb="10"
          >
            <p
              w:text="lg"
            >
              22. Dez.
            </p>
            <div
              w:flex="~ row"
              w:mt="2"
            >
              <div
              w:w="10"
              w:h="10"
              w:rounded="full"
              w:mb="4"
              w:mr="3"
              w:overflow="hidden"
              w:flex="shrink-0"
              class="highlight-bg"
              >
                <img src="https://fakeimg.pl/40/" alt="Dummy Image" w:max-w="10" w:h="auto" />
              </div>
              <div
                w:flex="~ col grow"
              >
                <p w:mb="1">Lorem Ipsum sit dolor</p>
                <p w:text="sm" class="highlight-text">Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet.</p>
              </div>
            </div>
          </div>
          <div
            w:mt="14"
            w:mb="10"
          >
            <div
              w:flex="~ row"
              w:justify="between"
              w:mb="10"
            >
              <span w:text="2xl">{{ $t('campaign.awards') }}</span>
            </div>
            <div
              w:flex="~ row"
              w:align="items-center"
              w:mt="2"
            >
              <div
              w:w="18"
              w:h="18"
              w:rounded="full"
              w:mr="3"
              w:overflow="hidden"
              w:flex="shrink-0"
              class="highlight-bg"
              >
                <img src="https://fakeimg.pl/72/" alt="Dummy Image" w:max-w="18" w:h="auto" />
              </div>
              <div
                w:flex="~ col grow"
              >
                <p w:mb="1">Lorem Ipsum sit dolor</p>
                <p w:text="sm" class="highlight-text">23.000 Punkte</p>
                <button
                  w:mt="2"
                  w:align="self-start"
                  class="btn-outline"
                >
                  Verschenken
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>

import { defineComponent, useAsync, useRoute, computed, useContext, ref, useStore } from '@nuxtjs/composition-api'
import { campaignApi } from '@/api/campaign'
import { postApi } from '@/api/post'
import { userApi } from '@/api/user'

export default defineComponent({
  name: 'CampaignPage',
  setup(props, ctx) {

    const route = useRoute()
    const context = useContext()
    const store = useStore()

    const postComment = ref(false)

    const { fetchCampaign } = campaignApi()
    const { fetchPostsByCampaign } = postApi()
    const { fetchUserInfoByCampaign } = userApi()

    const isLargerThanLg = computed(() => {
      return context.$breakpoints.lLg
    })

    let campaign = null
    let posts = null

    if (route.value.params.id) {
      campaign = useAsync(() => fetchCampaign(route.value.params.id), `campaign-${route.value.params.id}`)
      posts = useAsync(() => fetchPostsByCampaign(route.value.params.id), `posts-${route.value.params.id}`)
      if (context.$auth.loggedIn) {
        context.$auth.$storage.setState('campaignScore', useAsync(() => fetchUserInfoByCampaign(route.value.params.id), `userinfo-${route.value.params.id}-${context.$auth.user.id}`))
      }
    }

    store.dispatch('showAddButton')

    return {
      postComment,
      campaign,
      posts,
      isLargerThanLg
    }
  }
})
</script>

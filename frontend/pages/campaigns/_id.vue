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
          <div v-if="posts.value">
            <PostItem
              v-for="(post, key) in posts.value"
              :key="key"
              :post="post"
              @updatePost="updatePost"
              @hideCommentsFromOtherPosts="hideCommentsFromOtherPosts"
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
    <div v-if="!isLargerThanLg" w:display="xl:hidden">
      <PageFooter />
    </div>
  </div>
</template>

<script>

import { defineComponent, useAsync, useRoute, useRouter, computed, useContext, ref, useStore, watch } from '@nuxtjs/composition-api'
import { campaignApi } from '@/api/campaign'
import { postApi } from '@/api/post'
import { userApi } from '@/api/user'

export default defineComponent({
  name: 'CampaignPage',
  setup(props) {

    const route = useRoute()
    const router = useRouter()
    const { $breakpoints, $auth } = useContext()
    const store = useStore()

    const postComment = ref(false)

    const newPost = computed(() => store.state.newPostOnCampaign)

    watch(newPost, (newValue) => {
      if (newValue === route.value.params.id) {
        loadCampaign()
        store.dispatch('resetNewPostOnCampaign')
      }
    })

    const { fetchCampaign } = campaignApi()
    const { fetchPost, fetchPostsByCampaign } = postApi()
    const { fetchUserInfoByCampaign } = userApi()

    const isLargerThanLg = computed(() => {
      return $breakpoints.lLg
    })

    let campaign = null
    const posts = ref(null)

    function loadCampaign() {
      if (route.value.params.id) {
        campaign = useAsync(() => fetchCampaign(route.value.params.id), `campaign-${route.value.params.id}`)
        posts.value = useAsync(() => fetchPostsByCampaign(route.value.params.id), `posts-${route.value.params.id}`)
        if (campaign.value && campaign.value.error) {
          router.push('/404')
        }
        if ($auth.loggedIn) {
          $auth.$storage.setState('campaignScore', useAsync(() => fetchUserInfoByCampaign(route.value.params.id), `userinfo-${route.value.params.id}-${$auth.user.id}`))
        }
      }
    }

    loadCampaign()

    function updatePost(postId) {
      posts.value.value.forEach(function(item, key) {
        if (item.id === postId) {
          fetchPost(postId).then(function(response) {
            posts.value.value[key].commentCount = response.commentCount
          })
        }
      })
    }

    function hideCommentsFromOtherPosts(postId) {
      posts.value.value.forEach(function(item, key) {
        if (item.id === postId) {
          posts.value.value[key].showComments = true
        } else {
          posts.value.value[key].showComments = false
        }
      })
    }

    store.dispatch('showAddButton')
    store.dispatch('setCurrentCampaign',route.value.params.id)

    return {
      postComment,
      campaign,
      posts,
      newPost,
      isLargerThanLg,
      loadCampaign,
      updatePost,
      hideCommentsFromOtherPosts
    }
  }
})
</script>

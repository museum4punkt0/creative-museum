<template>
  <div>
    <div class="lg:grid lg:grid-cols-12 lg:gap-4">
      <div class="lg:col-span-3 pr-5">
        <div v-if="isLargerThanLg">
          <UserCampaignInfo v-if="campaign" :campaign="campaign" />
        </div>
      </div>
      <div class="lg:col-span-6">
        <PostItem v-if="post" :post="post"></PostItem>
      </div>
      <div class="lg:col-span-3 pl-5">
        <div v-if="isLargerThanLg">
          <SidebarRight v-if="campaign" :campaign="campaign" />
        </div>
      </div>
    </div>
    <div v-if="!isLargerThanLg" class="xl:hidden">
      <PageFooter />
    </div>
  </div>
</template>
<script>

import { defineComponent, useRoute, useRouter, useContext } from '@nuxtjs/composition-api'

import { campaignApi } from '@/api/campaign'
import { postApi } from '@/api/post'

export default defineComponent({
  setup() {
    const route = useRoute()
    const router = useRouter()
    const { $breakpoints } = useContext()


    const post = ref(null)
    const campaign = ref(null)

    const { fetchCampaign } = campaignApi()
    const { fetchPost } = postApi()

    const isLargerThanLg = computed(() => {
      return $breakpoints.lLg
    })

    async function loadPost() {
      if (route.value.params.id) {
        post.value = await fetchPost(
          route.value.params.id
        )
        campaign.value = await fetchCampaign(post.campaign.id)

        if (campaign.value && campaign.value.error) {
          router.push('/404')
        } else {
          store.dispatch('setCurrentCampaign', route.value.params.id)
        }
      }
    }

    onMounted(async () => {
      await loadPost()
    })

    return {
      post,
      campaign,
      isLargerThanLg,
      loadPost
    }

  },
})
</script>

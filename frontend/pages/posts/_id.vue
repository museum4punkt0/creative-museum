<template>
  <div>
    <div class="lg:grid lg:grid-cols-12 lg:gap-4">
      <div class="lg:col-span-3 pr-5">
        <div v-if="isLargerThanLg">
          <UserCampaignInfo v-if="campaign" :campaign="campaign" />
        </div>
      </div>
      <div class="lg:col-span-6">
        <div class="page-header lg:mt-0">
          <NuxtLink
            v-if="campaign"
            :to="`/campaigns/${campaign.id}`"
            class="back-btn"
          >
            {{ campaign.title }}
          </NuxtLink>
        </div>
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
import { TinyColor, readability } from '@ctrl/tinycolor'
import {
  defineComponent,
  computed,
  ref,
  onMounted,
  useRoute,
  useRouter,
  useContext,
  useStore,
  useMeta
} from '@nuxtjs/composition-api'

import { campaignApi } from '@/api/campaign'
import { postApi } from '@/api/post'

export default defineComponent({
  setup() {
    const route = useRoute()
    const router = useRouter()
    const { title } = useMeta()
    const store = useStore()
    const { $breakpoints, i18n } = useContext()

    const post = ref(null)
    const campaign = ref(null)

    const { fetchCampaign } = campaignApi()
    const { fetchPost } = postApi()

    const isLargerThanLg = computed(() => {
      return $breakpoints.lLg
    })

    function getContrastColor(color) {
      const bgColor = new TinyColor(color)
      const fgColor = new TinyColor('#FFFFFF')
      return readability(bgColor, fgColor) > 2 ? '#FFFFFF' : '#000000'
    }

    async function loadPost() {
      if (route.value.params.id) {
        await fetchPost(route.value.params.id).then(async function (response) {
          post.value = response
          campaign.value = await fetchCampaign(response.campaign.id)
          if (post.value && post.value.error) {
            router.push('/404')
          } else {

            title.value = i18n.t('post.details') + ' | ' + i18n.t('pageTitle')
            store.dispatch('setCurrentCampaign', response.campaign.id)

            document.documentElement.style.setProperty(
              '--highlight',
              response.campaign.color
            )

            document.documentElement.style.setProperty(
              '--highlight-contrast',
              getContrastColor(response.campaign.color)
            )
          }
        })
      }
    }

    onMounted(async () => {
      await loadPost()
    })

    return {
      post,
      campaign,
      isLargerThanLg,
      loadPost,
      getContrastColor,
    }
  },
  head: {}
})
</script>

<template>
  <div class="lg:grid lg:grid-cols-12 lg:gap-4 ">
    <div v-if="$auth.loggedIn" class="lg:col-span-3 lg:pr-10 lg:order-1 mb-6 lg:mb-0">
      <SidebarLeft />
    </div>
    <div :class="['content', $auth.loggedIn ? 'lg:col-span-9 lg:pr-10 lg:order-2' : 'lg:col-span-12']">
      <div class="my-3" v-html="accessibility" />
      <PageVideo v-if="video" :video="video" />
    </div>
    <div v-if="!isLargerThanLg || !$auth.loggedIn" :class="['lg:col-span-12' ,$auth.loggedIn ? 'xl:hidden' : '']">
      <PageFooter />
    </div>
  </div>
</template>

<script>
import { defineComponent, useStore, ref, onMounted, useContext, computed, useMeta } from '@nuxtjs/composition-api'
import cmsPage from '~/mixins/page/cmsPage'
import { cmsApi } from '~/api/cms'

export default defineComponent({
  mixins: [cmsPage],
  name: 'AccessibilityPage',
  setup() {
    const store = useStore()
    const { fetchAccessibility } = cmsApi()

    const { $breakpoints, i18n } = useContext()

    const isLargerThanLg = computed(() => {
      return $breakpoints.lLg
    })

    const accessibility = ref(null)
    const video = ref(null)

    useMeta({
      title: i18n.t('pages.accessibility.title') + ' | ' + i18n.t('pageTitle')
    })

    async function getAccessibilityPage() {
      const data = await fetchAccessibility()
      accessibility.value = JSON.parse(data.content)
      video.value = JSON.parse(data.video)
    }

    onMounted(() => {
      getAccessibilityPage()
    })

    store.dispatch('hideAddButton')
    store.dispatch('setCurrentCampaign', null)

    return {
      accessibility,
      video,
      isLargerThanLg
    }
  },
  head: {}
})
</script>

<template>
  <div class="grid lg:grid-cols-12 lg:gap-4">
    {{ /* Content */ }}
    <div class="content col-span-12 pt-5 lg:pt-0">
      <div class="my-3" v-html="about" />
      <PageVideo v-if="video" :video="video" />
    </div>

    {{ /* Footer */ }}
    <div class="col-span-12">
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
  name: 'AboutPage',
  setup() {
    const store = useStore()
    const { fetchAbout } = cmsApi()

    const { $breakpoints, i18n } = useContext()

    const isLargerThanLg = computed(() => {
      return $breakpoints.lLg
    })

    const about = ref(null)
    const video = ref(null)

    useMeta({
      title: i18n.t('pages.about.title') + ' | ' + i18n.t('pageTitle')
    })

    async function getAboutPage() {
      const data = await fetchAbout()
      about.value = JSON.parse(data.content)
      video.value = JSON.parse(data.video)
    }

    onMounted(() => {
      getAboutPage()
    })

    store.dispatch('hideAddButton')
    store.dispatch('setCurrentCampaign', null)


    return {
      about,
      video,
      isLargerThanLg
    }
  },
  head: {}
})
</script>

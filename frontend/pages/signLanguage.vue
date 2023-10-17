<template>
  <div class="grid lg:grid-cols-12 lg:gap-4">
    {{ /* Content */ }}
    <div class="content col-span-12 pt-5 lg:pt-0">
      <div class="my-3" v-html="signLanguage" />
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
  name: 'SignLanguagePage',
  setup() {
    const store = useStore()
    const { fetchSignLanguage } = cmsApi()

    const { $breakpoints, i18n } = useContext()

    const isLargerThanLg = computed(() => {
      return $breakpoints.lLg
    })

    const signLanguage = ref(null)
    const video = ref(null)

    useMeta({
      title: i18n.t('pages.signLanguage.title') + ' | ' + i18n.t('pageTitle')
    })

    async function getSignLanguagePage() {
      const data = await fetchSignLanguage()
      signLanguage.value = JSON.parse(data.content)
      video.value = JSON.parse(data.video)
    }

    onMounted(() => {
      getSignLanguagePage()
    })

    store.dispatch('hideAddButton')
    store.dispatch('setCurrentCampaign', null)

    return {
      signLanguage,
      video,
      isLargerThanLg
    }
  },
  head: {}
})
</script>

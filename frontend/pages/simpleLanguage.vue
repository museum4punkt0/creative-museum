<template>
  <div class="grid lg:grid-cols-12 lg:gap-4">
    {{ /* Content */ }}
    <div class="content col-span-12 pt-5 lg:pt-0">
      <div class="my-3" v-html="simpleLanguage" />
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
  name: 'SimpleLanguagePage',
  setup() {
    const store = useStore()
    const { fetchSimpleLanguage } = cmsApi()

    const { $breakpoints, i18n } = useContext()

    const isLargerThanLg = computed(() => {
      return $breakpoints.lLg
    })

    const simpleLanguage = ref(null)
    const video = ref(null)

    useMeta({
      title: i18n.t('pages.simpleLanguage.title') + ' | ' + i18n.t('pageTitle')
    })

    async function getSimpleLanguagePage() {
      const data = await fetchSimpleLanguage()
      simpleLanguage.value = JSON.parse(data.content)
      video.value = JSON.parse(data.video)
    }

    onMounted(() => {
      getSimpleLanguagePage()
    })

    store.dispatch('hideAddButton')
    store.dispatch('setCurrentCampaign', null)


    return {
      simpleLanguage,
      video,
      isLargerThanLg
    }
  },
  head: {}
})
</script>

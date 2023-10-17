<template>
  <div class="grid lg:grid-cols-12 lg:gap-4">
    {{ /* Content */ }}
    <div class="content col-span-12 pt-5 lg:pt-0">
      <div class="my-3" v-html="imprint" />
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
  name: 'ImprintPage',
  setup() {
    const store = useStore()
    const { fetchImprint } = cmsApi()

    const { $breakpoints, i18n } = useContext()

    useMeta({
      title: i18n.t('pages.imprint.title') + ' | ' + i18n.t('pageTitle')
    })

    const isLargerThanLg = computed(() => {
      return $breakpoints.lLg
    })

    const imprint = ref(null)
    const video = ref(null)

    async function getImprintPage() {
      const data = await fetchImprint()
      imprint.value = JSON.parse(data.content)
      video.value = JSON.parse(data.video)
    }

    onMounted(() => {
      getImprintPage()
    })

    store.dispatch('hideAddButton')
    store.dispatch('setCurrentCampaign', null)

    return {
      imprint,
      isLargerThanLg
    }
  },
  head: {}
})
</script>

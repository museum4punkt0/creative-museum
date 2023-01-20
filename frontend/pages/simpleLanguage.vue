<template>
  <div class="lg:grid lg:grid-cols-12 lg:gap-4 ">
    <div v-if="$auth.loggedIn" class="lg:col-span-3 lg:pr-10 lg:order-1 mb-6 lg:mb-0">
      <SidebarLeft />
    </div>
    <div :class="['content', $auth.loggedIn ? 'lg:col-span-9 lg:pr-10 lg:order-2' : 'lg:col-span-12']">
      <div class="my-3" v-html="simpleLanguage" />
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
  setup() {
    const store = useStore()
    const { fetchSimpleLanguage } = cmsApi()

    const { $breakpoints, i18n } = useContext()

    const isLargerThanLg = computed(() => {
      return $breakpoints.lLg
    })

    const simpleLanguage = ref(null)

    useMeta({
      title: i18n.t('pages.simpleLanguage.title') + ' | ' + i18n.t('pageTitle')
    })

    async function getSimpleLanguagePage() {
      const simpleLanguageData = await fetchSimpleLanguage()
      simpleLanguage.value = JSON.parse(simpleLanguageData.content)
    }

    onMounted(() => {
      getSimpleLanguagePage()
    })

    store.dispatch('hideAddButton')
    store.dispatch('setCurrentCampaign', null)


    return {
      simpleLanguage,
      isLargerThanLg
    }
  },
  head: {}
})
</script>

<template>
<div>
    <div class="my-3" v-html="simpleLanguage" />
  </div>
</template>

<script>
import { defineComponent, useStore, ref } from '@nuxtjs/composition-api'
import { cmsApi } from "~/api/cms";

export default defineComponent({
  setup() {
    const store = useStore()
    const { fetchSimpleLanguage } = cmsApi()

    const simpleLanguage = ref(null)

    async function getSimpleLanguagePage() {
      const simpleLanguageData = await fetchSimpleLanguage()
      simpleLanguage.value = JSON.parse(simpleLanguageData.content)
    }

    getSimpleLanguagePage()

    store.dispatch('hideAddButton')
    store.dispatch('setCurrentCampaign', null)


    return {
      simpleLanguage
    }
  },
})
</script>

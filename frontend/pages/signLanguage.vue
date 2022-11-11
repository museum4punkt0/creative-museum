<template>
<div>
    <div class="my-3" v-html="signLanguage" />
  </div>
</template>

<script>
import { defineComponent, useStore, ref } from '@nuxtjs/composition-api'
import { cmsApi } from "~/api/cms";

export default defineComponent({
  setup() {
    const store = useStore()
    const { fetchSignLanguage } = cmsApi()

    const signLanguage = ref(null)

    async function getSignLanguagePage() {
      const aboutData = await fetchSignLanguage()
      signLanguage.value = JSON.parse(aboutData.content)
    }

    getSignLanguagePage()

    store.dispatch('hideAddButton')
    store.dispatch('setCurrentCampaign', null)


    return {
      signLanguage
    }
  },
})
</script>

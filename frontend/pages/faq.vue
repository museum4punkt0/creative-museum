<template>
  <div>
    <div class="my-3" v-html="faq" />
  </div>
</template>

<script>
import { defineComponent, useStore, ref } from '@nuxtjs/composition-api'
import { cmsApi } from "~/api/cms";

export default defineComponent({
  setup() {
    const store = useStore()
    const { fetchFaq } = cmsApi()

    const faq = ref(null)

    async function getFaqPage() {
      const faqData = await fetchFaq()
      faq.value = JSON.parse(faqData.content)
    }

    getFaqPage()

    store.dispatch('hideAddButton')
    store.dispatch('setCurrentCampaign', null)


    return {
      faq
    }
  },
})
</script>

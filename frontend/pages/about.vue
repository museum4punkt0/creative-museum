<template>
  <div>
    <div class="my-3" v-html="about" />
  </div>
</template>

<script>
import { defineComponent, useStore, ref } from '@nuxtjs/composition-api'
import { cmsApi } from "~/api/cms";

export default defineComponent({
  setup() {
    const store = useStore()
    const { fetchAbout } = cmsApi()

    const about = ref(null)

    async function getAboutPage() {
      const aboutData = await fetchAbout()
      about.value = JSON.parse(aboutData.content)
    }

    getAboutPage()

    store.dispatch('hideAddButton')
    store.dispatch('setCurrentCampaign', null)


    return {
      about
    }
  },
})
</script>

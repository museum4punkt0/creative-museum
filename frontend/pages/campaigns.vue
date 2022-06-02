<template>
  <div>
    {{ campaigns }}
  </div>
</template>
<script>

import { defineComponent, ref, useFetch, useContext } from '@nuxtjs/composition-api'

export default defineComponent({
  setup() {

    const { $axios } = useContext()
    const campaigns = ref('')

    useFetch(async () => {
      campaigns.value = await $axios.$get('https://backend.creative-museum.ddev.site/v1/campaigns')
    })

    return { campaigns }

  },
})
</script>
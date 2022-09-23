<template>
  <div>

  </div>
</template>
<script>
import {
    defineComponent,
    useContext,
    computed,
    onMounted,
    ref,
  } from '@nuxtjs/composition-api'
import { awardApi } from '@/api/award'

export default defineComponent({
  props: {
    post: {
      type: Object,
      required: true
    }
  },
  setup(props) {

    const { fetchAvailableAwards } = awardApi()
    const availableAwards = ref(null)

    onMounted(async () => {
      await getAvailableAwards()
    })

    async function getAvailableAwards() {
      await fetchAvailableAwards(props.post.campaign.id)
    }

    return {
      availableAwards,
      getAvailableAwards
    }
  },
})
</script>

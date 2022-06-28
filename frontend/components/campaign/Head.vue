<template>
  <div>
    <div w:m="b-6">
      <h1 class="page-header" w:m="t-0 b-1">{{ campaign.title }}</h1>
      <div w:text="lg"><span w:text="capitalize">{{ $t('till')}}</span> {{ $dayjs(campaign.end).format('DD.MM.YYYY') }}</div>
    </div>
    <div>{{campaign.shortDescription}}</div>
  </div>
</template>
<script>
import { defineComponent } from '@nuxtjs/composition-api'

export default defineComponent({
  props: {
    campaign: {
      type: Object,
      required: true
    }
  },
  setup(props) {
  }
})
</script>

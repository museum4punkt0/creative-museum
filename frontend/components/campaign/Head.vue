<template>
  <div>
    <h1 class="page-header" w:m="t-0 b-1">{{ campaign.title }}</h1>
    <div><span w:text="capitalize">{{ $t('till')}}</span> {{ $dayjs(campaign.end).format('DD.MM.YYYY') }}</div>
    <div>{{campaign.shortDescription}}</div>
    <div><Nl2br tag="p" :text="campaign.description" /></div>
  </div>
</template>
<script>
import { defineComponent } from '@nuxtjs/composition-api'
import Nl2br from 'vue-nl2br'

export default defineComponent({
  components: {
    Nl2br
  },
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

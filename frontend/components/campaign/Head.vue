<template>
  <div>
    <style>
      :root {
        --highlight: {{ campaign.color }};
      }
    </style>
    <div w:m="b-6">
      <h1 class="page-header" w:m="t-0 b-1">{{ campaign.title }}</h1>
      <div w:text="lg"><span w:text="capitalize">{{ $t('till')}}</span> {{ $dayjs(campaign.end).format('DD.MM.YYYY') }}</div>
    </div>
    <div v-show="!showLongDescription">
      <p w:m="b-6" v-html="formattedShortDescription" />
      <a v-if="formattedShortDescription !== formattedDescription" class="highlight-text" href="#" @click.prevent="showLongDescription = true">{{ $t('readMore') }}</a>
    </div>
    <div v-show="showLongDescription">
      <p w:m="b-6" v-html="formattedDescription" />
      <a class="highlight-text" href="#" @click.prevent="showLongDescription = false">{{ $t('readLess') }}</a>
    </div>
  </div>
</template>
<script>
import { defineComponent, computed, ref } from '@nuxtjs/composition-api'
export default defineComponent({
  props: {
    campaign: {
      type: Object,
      required: true
    }
  },
  setup(props) {
    const showLongDescription = ref(false)
    const formattedShortDescription = computed(() => {
      return props.campaign.description.split(' ').splice(0, 50).join(' ').replace(/(?:\r\n|\r|\n)/g, '<br />')
    })
    const formattedDescription = computed(() => {
      return props.campaign.description.replace(/(?:\r\n|\r|\n)/g, '<br />')
    })

    return {
      formattedShortDescription,
      formattedDescription,
      showLongDescription
    }
  }
})
</script>
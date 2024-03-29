<template>
  <div>
    <component :is="`${type}Icon`" v-if="image && !showLongDesc" :image="image" :title="title" class="h-40 w-auto mx-auto" />
    <a class="text-$highlight" :href="'/campaigns/' + campaign.id">{{ campaign.title }}</a>
    <h1 class="text-2xl">{{ title }}</h1>
    <div v-if="formattedShortDescription !== formattedDescription">
      <p v-html="showLongDesc ? formattedDescription : formattedShortDescription" />
      <button class="text-$highlight text-sm mt-4" @click.prevent="showLongDesc = ! showLongDesc">{{ showLongDesc ? $t('readLess') : $t('readMore') }}</button>
    </div>
    <div v-else>
      <p v-html="formattedDescription" />
    </div>
    <div v-if="type === 'Award'" class="text-$highlight mt-4">
      {{ price.toLocaleString() + ' ' + $t('points') }}
    </div>
    <a v-if="link" :href="link" class="link-arrow block mt-4" target="_blank">{{ $t('badges.linkToCatalog') }}</a>
  </div>
</template>
<script>
import { defineComponent, ref, computed } from '@nuxtjs/composition-api'

export default defineComponent({
  props: {
    type: {
      type: String,
      required: true
    },
    title: {
      type: String,
      required: true
    },
    text: {
      type: String,
      default: ''
    },
    image: {
      type: Object,
      default: () => {}
    },
    link: {
      type: String,
      default: ''
    },
    price: {
      type: Number,
      default: 0
    },
    campaign: {
      type: Object,
      default: () => {}
    },
  },
  setup(props) {
    const showLongDesc = ref(false)

    const formattedShortDescription = computed(() => {
      return props.text
        ? props.text
            .split(' ')
            .splice(0, 20)
            .join(' ')
            .replace(/(?:\r\n|\r|\n)/g, '<br />')
        : ''
    })

    const formattedDescription = computed(() => {
      return props.text
        ? props.text.replace(/(?:\r\n|\r|\n)/g, '<br />')
        : ''
    })
    return {
      showLongDesc,
      formattedDescription,
      formattedShortDescription
    }
  },
})
</script>

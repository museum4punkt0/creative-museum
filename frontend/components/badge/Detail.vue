<template>
  <div :style="styleAttr" class="flex flex-col flex-1 justify-between h-full">
    <div class="page-header px-6 self-start">
      <a class="back-btn" @click.prevent="$emit('closebadgeDetail')">
        {{ $t('badges.detailHeadline') }}
      </a>
    </div>
    <div class="flex-1">
      <div class="box-shadow relative m-6">
        <AwardBadgeDetailText type="Badge" :title="badge.title" :text="badge.description" :image="badge.picture" :link="badge.link" />
      </div>
    </div>
    <div class="mx-6 mb-6">
      <button
        class="btn-outline w-full"
        @click.prevent="$emit('closebadgeDetail')"
      >
        {{ $t('close') }}
      </button>
    </div>
  </div>
</template>
<script>
import { defineComponent, useContext, computed, ref } from '@nuxtjs/composition-api'
import { TinyColor, readability } from '@ctrl/tinycolor'

export default defineComponent({
  props: {
    badge: {
      type: Object,
      required: true,
    },
  },
  emits: ['closebadgeDetail'],
  setup(props) {
    const context = useContext()

    const showLongDesc = ref(false)

    const formattedShortDescription = computed(() => {
      return props.badge.description
        ? props.badge.description
            .split(' ')
            .splice(0, 20)
            .join(' ')
            .replace(/(?:\r\n|\r|\n)/g, '<br />')
        : ''
    })

    const formattedDescription = computed(() => {
      return props.badge.description
        ? props.badge.description.replace(/(?:\r\n|\r|\n)/g, '<br />')
        : ''
    })

    const bgColor = new TinyColor(props.badge.campaign.color)
    const fgColor = new TinyColor('#FFFFFF')
    const altfgColor = new TinyColor('#222329')

    const test1 = readability(bgColor, fgColor)
    const test2 = readability(bgColor, altfgColor)

    const campaignContrastColor = computed(() => {
      return (test1 < test2) ? '#222329' : '#ffffff'
    })

    const styleAttr = computed(() => {
      return `--highlight: ${props.badge.campaign.color}; --highlight-contrast: ${campaignContrastColor.value};`
    })
    return {
      formattedShortDescription,
      formattedDescription,
      showLongDesc,
      backendURL: context.$config.backendURL,
      styleAttr,
    }
  },
})
</script>

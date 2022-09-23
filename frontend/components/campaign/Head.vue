<template>
  <div>
    <style type="text/css">
      body {
        --highlight: {{ campaign.color }};
        --highlight-contrast: {{ campaignContrastColor }};
      }
    </style>
    <div class="mb-6">
      <h1 class="page-header lg:mt-0 mb-1">{{ campaign.title }}</h1>
      <div class="text-lg">
        <span class="capitalize">{{ $t('till') }}</span>
        {{ $dayjs(campaign.stop).format('DD.MM.YYYY') }}
      </div>
    </div>
    <div v-show="!showLongDescription">
      <p class="mb-6" v-html="formattedShortDescription" />
      <a
        v-if="formattedShortDescription !== formattedDescription"
        class="highlight-text"
        href="#"
        @click.prevent="showLongDescription = true"
        >{{ $t('readMore') }}</a
      >
    </div>
    <div v-show="showLongDescription">
      <p class="mb-6" v-html="formattedDescription" />
      <a
        class="highlight-text"
        href="#"
        @click.prevent="showLongDescription = false"
        >{{ $t('readLess') }}</a
      >
    </div>
    <div v-if="!isLargerThanLg" class="xl:hidden">
      <div v-if="$auth.loggedIn">
        <p class="text-lg font-bold mt-10 mb-3">
          {{ $t('user.yourCurrentScore') }}
        </p>
        <UserScore :campaign="campaign" />
      </div>
      <CampaignFilter :campaign="campaign" class="mt-10" />
    </div>
  </div>
</template>
<script>
import {
  defineComponent,
  useContext,
  computed,
  ref,
} from '@nuxtjs/composition-api'

import { TinyColor, readability } from '@ctrl/tinycolor'

export default defineComponent({
  props: {
    campaign: {
      type: Object,
      default: () => {},
    },
  },
  setup(props) {
    const context = useContext()
    const showLongDescription = ref(false)

    const formattedShortDescription = computed(() => {
      return props.campaign.description
        ? props.campaign.description
            .split(' ')
            .splice(0, 50)
            .join(' ')
            .replace(/(?:\r\n|\r|\n)/g, '<br />')
        : ''
    })

    const formattedDescription = computed(() => {
      return props.campaign.description
        ? props.campaign.description.replace(/(?:\r\n|\r|\n)/g, '<br />')
        : ''
    })

    const isLargerThanLg = computed(() => {
      return context.$breakpoints.lLg
    })

    const bgColor = new TinyColor(props.campaign.color)
    const fgColor = new TinyColor('#FFFFFF')

    const campaignContrastColor = computed(() => {
      return readability(bgColor, fgColor) > 2 ? '#FFFFFF' : '#000000'
    })

    return {
      formattedShortDescription,
      formattedDescription,
      showLongDescription,
      isLargerThanLg,
      campaignContrastColor,
    }
  },
})
</script>

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
      <p class="text-lg">
        <span class="capitalize">{{ $t('till') }}</span>
        {{ $dayjs(campaign.stop).format($t('dateFormat')) }}
      </p>
    </div>
    <div v-show="!showLongDescription">
      <div class="mb-6" :class="formattedShortDescription !== formattedDescription ? 'max-h-[5em] overflow-hidden relative campaign-description campaign-description-short' : ''" v-html="campaign.description" />
      <button
        v-if="formattedShortDescription !== formattedDescription"
        class="highlight-text"
        :aria-label="$t('readMoreLong')"
        @click.prevent="showLongDescription = true"
        >{{ $t('readMore') }}</button
      >
    </div>
    <div v-show="showLongDescription">
      <div class="mb-6 campaign-description" v-html="campaign.description" />
      <button
        class="highlight-text"
        :aria-label="$t('readLessLong')"
        @click.prevent="showLongDescription = false"
        >{{ $t('readLess') }}</button
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
    const altfgColor = new TinyColor('#222329')

    const test1 = readability(bgColor, fgColor)
    const test2 = readability(bgColor, altfgColor)

    const campaignContrastColor = computed(() => {
      return (test1  < test2) ? '#222329' : '#FFFFFF'
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
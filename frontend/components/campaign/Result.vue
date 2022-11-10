<template>
  <div v-if="campaignResult" class="mt-10" :style="styleAttr">
    <div class="box-shadow">
      <div class="flex flex-row justify-start mb-4">
        <div class="rounded-full w-8 h-8 bg-$highlight mr-4" />
        <div class="flex flex-col">
          <span class="text-lg">{{ $t('system') }}</span>
          <span class="text-sm text-$highlight mt-1">{{
            $dayjs.duration($dayjs().diff($dayjs(campaignClosed))).days() > 2
              ? $dayjs(campaignClosed).format($t('dateFormat'))
              : $dayjs(campaignClosed).locale($i18n.locale).fromNow()
          }}</span>
        </div>
      </div>
      <div>
        <p class="mb-4">
          {{
            $t('campaign.closed.result.description', {
              campaign: campaignTitle,
            })
          }}
        </p>
        <div v-if="!showAllResults">
          <ul>
            <li
              v-for="(campaignResultItem, key) in campaignResulTop5"
              :key="key"
              class="mb-4"
            >
              <CampaignResultItem :parent-key="key" :campaign-result-item="campaignResultItem" :reward-points="campaignResult[0].rewardPoints" />
            </li>
          </ul>
          <button v-if="campaignResult.length > 5" class="mt-6 text-$highlight" @click.prevent="showAllResults = true">{{ $t('readMore') }}</button>
        </div>
        <div v-else>
          <ul>
            <li
              v-for="(campaignResultItem, key) in campaignResult"
              :key="key"
              class="mb-4"
            >
              <CampaignResultItem :parent-key="key" :campaign-result-item="campaignResultItem" :reward-points="campaignResult[0].rewardPoints" />
            </li>
          </ul>
          <button class="mt-6 text-$highlight" @click.prevent="showAllResults = false">{{ $t('readLess') }}</button>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import { defineComponent, computed, ref } from '@nuxtjs/composition-api'
import { TinyColor, readability } from '@ctrl/tinycolor'

export default defineComponent({
  props: {
    campaignTitle: {
      type: String,
      required: true,
    },
    campaignResult: {
      type: Array,
      required: true,
    },
    campaignColor: {
      type: String,
      required: true,
    },
    campaignClosed: {
      type: String,
      required: true,
    },
  },
  setup(props) {
    const bgColor = new TinyColor(props.campaignColor)
    const fgColor = new TinyColor('#FFFFFF')

    const showAllResults = ref(false)

    const campaignContrastColor = computed(() => {
      return readability(bgColor, fgColor) > 2 ? '#FFFFFF' : '#000000'
    })

    const campaignResulTop5 = computed(() => {
      return props.campaignResult.slice(0,4)
    })

    const styleAttr = computed(() => {
      return `--highlight: ${props.campaignColor}; --highlight-contrast: ${campaignContrastColor.value};`
    })

    return {
      campaignResulTop5,
      showAllResults,
      styleAttr,
    }
  },
})
</script>

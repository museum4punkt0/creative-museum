<template>
  <div class="mt-10" :style="styleAttr">
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
        <ul>
          <li
            v-for="(campaignResultItem, key) in campaignResult"
            :key="key"
            class="mb-4"
          >
            <NuxtLink
              :to="
                $auth.loggedIn && campaignResultItem.user.uuid === $auth.user.uuid
                  ? localePath('/user/profile')
                  : localePath(`/user/${campaignResultItem.user.uuid}`)
              "
              class="block mb-2"
              >{{ key + 1 }}. {{ $userName(campaignResultItem.user) }}</NuxtLink
            >
            <div class="box-shadow-inset rounded-xl">
              <div
                class="bg-$highlight rounded-xl text-$highlight-contrast text-center"
                :style="`width: ${Math.round(
                  (100 / campaignResult[0].rewardPoints) *
                    campaignResultItem.rewardPoints
                )}%`"
              >
                <span
                  class="px-3 py-0.5 inline-block whitespace-nowrap"
                  :class="
                    Math.round(
                      (100 / campaignResult[0].rewardPoints) *
                        campaignResultItem.rewardPoints
                    ) < 30
                      ? 'text-white'
                      : ''
                  "
                  >{{ campaignResultItem.rewardPoints }} Awards</span
                >
              </div>
            </div>
          </li>
        </ul>
      </div>
    </div>
  </div>
</template>
<script>
import { defineComponent, computed } from '@nuxtjs/composition-api'
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

    const campaignContrastColor = computed(() => {
      return readability(bgColor, fgColor) > 2 ? '#FFFFFF' : '#000000'
    })

    const styleAttr = computed(() => {
      return `--highlight: ${props.campaignColor}; --highlight-contrast: ${campaignContrastColor.value};`
    })

    return {
      styleAttr,
    }
  },
})
</script>

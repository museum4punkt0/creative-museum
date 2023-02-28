<template>
  <div :style="styleAttr">
    <div
      v-if="post.type !== 'system'"
      class="box-shadow"
      :class="[
        post.type === 'playlist' ? `highlight-bg text-$highlight-contrast` : 'bg-grey',
      ]"
    >
      <KioskPostHead
        :post="post"
        class="mb-4"
        :text-color="textColor"
      />
      <component
        :is="componentName"
        :post="post"
        display-mode="Kiosk"
        class="mb-4"
      />
      <KioskPostFooter
        :post="post"
        :text-color="textColor"
      />
      <KioskPostComments
        :post="post"
      />
    </div>
  </div>
</template>
<script>
import { defineComponent, computed, ref } from '@nuxtjs/composition-api'
import { TinyColor, readability } from '@ctrl/tinycolor'
import { feedbackApi } from '@/api/feedback'

export default defineComponent({
  props: {
    post: {
      type: Object,
      required: true,
    },
  },
  emits: ['updatePost', 'postDeleted'],
  setup(props, context) {
    const { getOptions, selectOption, getFeedbackResults } = feedbackApi()

    const showFeedbackForm = ref(false)
    const voted = ref(false)
    const feedbackOptions = ref([])
    const selectedFeedbackOptions = ref(null)
    const total = ref(0)

    const componentName = computed(() => {
      return props.post.type
        ? 'PostTypes' +
            props.post.type.charAt(0).toUpperCase() +
            props.post.type.slice(1)
        : false
    })

    const postMetadata = computed(() => {
      if (!props.post.postMetadata) return
      return JSON.parse(props.post.postMetadata)
    })

    const textColor = getContrastColorClass()

    const bgColor = new TinyColor(props.post.campaign.color)
    const fgColor = new TinyColor('#FFFFFF')
    const altfgColor = new TinyColor('#222329')

    const test1 = readability(bgColor, fgColor)
    const test2 = readability(bgColor, altfgColor)

    const campaignContrastColor = computed(() => {
      return (test1 < test2) ? '#222329' : '#FFFFFF'
    })

    function getContrastColorClass() {
      return (test1 < test2) ? 'contrast' : 'white'
    }

    const styleAttr = computed(() => {
      return `--highlight: ${props.post.campaign.color}; --highlight-contrast: ${campaignContrastColor.value};`
    })

    async function getResults() {
      const results = await getFeedbackResults(props.post.id)
      total.value = results.length > 0 ? 0 : 100

      feedbackOptions.value.forEach((option, index) => {
        feedbackOptions.value[index].sum = 0
        for (const result of results) {
          if (result.id !== option.id) {
            continue
          }
          feedbackOptions.value[index].sum = result.amount
          total.value += result.amount
        }
      })
    }

    async function triggerFeedback() {
      feedbackOptions.value = await getOptions(props.post.campaign.id)

      if (props.post.rated || voted.value || !props.post.campaign.active || props.post.campaign.closed) {
        await getResults()
        voted.value = true
      }

      showFeedbackForm.value = true
    }

    async function voteOption(optionId) {
      if (voted.value || feedbackOptions.value.length === 0) {
        return
      }
      await selectOption(props.post.id, optionId)
      await getResults()

      voted.value = true
      context.emit('updatePost', props.post.id)
    }

    return {
      getContrastColorClass,
      triggerFeedback,
      voteOption,
      campaignContrastColor,
      componentName,
      textColor,
      styleAttr,
      showFeedbackForm,
      selectedFeedbackOptions,
      feedbackOptions,
      voted,
      total,
      postMetadata,
    }
  },
})
</script>

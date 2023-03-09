<template>
  <div :style="styleAttr">
    <div
      v-if="post.type !== 'system'"
      class="box-shadow max-h-[calc(100vh-15rem)] overflow-hidden"
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
import { feedbackApi } from '@/api/feedback'
import { useContrastColor, useContrastColorClass } from '~/mixins/components/ContrastColor'

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

    const textColor = useContrastColorClass(props.post.campaign.color)
    const campaignContrastColor = useContrastColor(props.post.campaign.color)

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

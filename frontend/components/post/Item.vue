<template>
  <div class="mt-10" :style="styleAttr">
    <div
      v-if="post.type !== 'system'"
      class="box-shadow"
      :class="[
        post.type === 'playlist' ? `highlight-bg text-$highlight-contrast` : '',
      ]"
    >
      <PostHead
        :post="post"
        class="mb-4"
        :text-color="textColor"
        @toggleBookmarkState="$emit('toggleBookmarkState', post.id)"
        @postDeleted="$emit('postDeleted')"
      />
      <component
        :is="componentName"
        :post="post"
        class="mb-4"
        @updatePost="$emit('updatePost', post.id)"
      />
      <PostFooter
        :post="post"
        :text-color="textColor"
        @triggerFeedback="triggerFeedback"
        @voted="$emit('updatePost', post.id)"
      />
      <PostComments
        :post="post"
        :expand-comments="expandComments"
        @commentsLoaded="$emit('updatePost', post.id)"
        @commentDeleted="$emit('updatePost', post.id)"
      />
    </div>
    <h2 v-else class="highlight-text text-center text-sm">
      {{
        $t(`campaign.systemMessages.${post.body}`, {
          giver: postMetadata.giver ? $userName(postMetadata.giver) : '',
          winner: postMetadata.winner ? $userName(postMetadata.winner) : '',
          award: postMetadata.award ? postMetadata.award.title : '',
        })
      }}
    </h2>

    <UtilitiesSlideUp
      v-if="showFeedbackForm"
      :closable="true"
      :aria-label="$t('modal.feedback')"
      @closeModal="showFeedbackForm = false"
    >
      <div class="p-6">
        <h3 class="mb-6 w-full">{{ $t('post.feedback') }}</h3>

        <template v-for="(option, key) in feedbackOptions">
          <button
            v-if="!voted && (post.campaign.active|| !post.campaign.closed)"
            :key="key"
            class="btn-primary btn-outline w-full mt-4"
            @click.prevent="voteOption(option.id)"
          >
            {{ option.text }}
          </button>
          <div v-if="voted || !post.campaign.active || post.campaign.closed" :key="key" class="mb-6">
            <div class="mb-2">{{ option.text }}</div>
            <div class="box-shadow-inset rounded-xl">
              <div
                class="bg-$highlight rounded-xl text-$highlight-contrast text-center"
                :style="`width: ${Math.round((100 / total) * option.sum)}%`"
              >
                <span
                  class="px-3 py-0.5 inline-block"
                  :class="
                    Math.round((100 / total) * option.sum) < 10
                      ? 'text-white'
                      : ''
                  "
                  >{{ Math.round((100 / total) * option.sum) }}%</span
                >
              </div>
            </div>
          </div>
        </template>
      </div>
    </UtilitiesSlideUp>
  </div>
</template>
<script>
import { defineComponent, computed, ref } from '@nuxtjs/composition-api'
import { feedbackApi } from '@/api/feedback'
import { useContrastColor, useContrastColorClass} from '~/mixins/components/ContrastColor'

export default defineComponent({
  props: {
    post: {
      type: Object,
      required: true,
    },
    expandComments: {
      type: Boolean,
      default: false
    }
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
      return `--highlight: ${props.post.campaign.color}; --highlight-contrast: ${campaignContrastColor};`
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

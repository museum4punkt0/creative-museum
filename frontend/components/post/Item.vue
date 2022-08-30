<template>
  <div class="mt-10">
    <div
      v-if="post.type !== 'system'"
      class="box-shadow"
      :class="[post.type === 'playlist' ? `text-${textColor} highlight-bg` : '']"
    >
      <PostHead
        :post="post"
        class="mb-4"
        :text-color="textColor"
        @toggle-bookmark-state="$emit('toggle-bookmark-state', post.id)"
      />
      <component :is="componentName" :post="post" class="mb-4" @updatePost="$emit('updatePost', post.id)" />
      <PostFooter
        :post="post"
        class="mb-4"
        :text-color="textColor"
        @triggerFeedback="triggerFeedback"
        @voted="$emit('updatePost', post.id)"
      />
      <PostComments
        :post="post"
        @commentsLoaded="$emit('updatePost', post.id)"
      />
    </div>
    <div v-else class="highlight-text text-center">
      <p>{{ post.body }}</p>
    </div>

    <SlideUp
      v-if="showFeedbackForm"
      :closable="true"
      @closeModal="showFeedbackForm = false"
    >
      <div class="p-6">
        <h3 class="mb-6 w-full">{{ $t('post.feedback') }}</h3>

        <template v-for="(option, key) in feedbackOptions">
          <button
            v-if="!voted"
            :key="key"
            class="btn-primary btn-outline w-full mt-4"
            @click.prevent="voteOption(option.id)"
          >
            {{ option.text }}
          </button>
          <div v-if="voted" :key="key" class="mb-6">
            <div class="mb-2">{{ option.text }}</div>
            <div class="box-shadow-inset rounded-xl">
              <div class="bg-$highlight rounded-xl text-$highlight-contrast text-center" :style="`width: ${Math.round((100 / total) * option.sum)}%`"><span class="px-3 py-0.5 inline-block" :class="Math.round((100 / total) * option.sum) < 10 ? 'text-white' : ''">{{ Math.round((100 / total) * option.sum) }}%</span></div>
            </div>
          </div>
        </template>
      </div>
    </SlideUp>
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
    campaignColor: {
      type: String,
      default: '#FFFF00',
    },
    campaignActive: {
      type: Boolean,
      default: true,
    },
  },
  emits: ['updatePost'],
  setup(props) {
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

    const textColor = getContrastColorClass()

    function getContrastColorClass() {
      const bgColor = new TinyColor(props.campaignColor)
      const fgColor = new TinyColor('#FFFFFF')
      return readability(bgColor, fgColor) > 2 ? 'white' : 'black'
    }

    async function getResults() {
      const results = await getFeedbackResults(props.post.id)
      total.value = 0

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

      if (props.post.rated || voted.value) {
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
    }

    return {
      componentName,
      textColor,
      getContrastColorClass,
      triggerFeedback,
      showFeedbackForm,
      selectedFeedbackOptions,
      feedbackOptions,
      voteOption,
      voted,
      total,
    }
  },
})
</script>

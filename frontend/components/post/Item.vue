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
      <component :is="componentName" :post="post" class="mb-4" />
      <PostFooter
        :post="post"
        class="mb-4"
        :text-color="textColor"
        @triggerFeedback="triggerFeedback"
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

        <template v-for="(option, index) in feedbackOptions">
          <button
            v-if="!voted"
            :key="index"
            class="btn-primary btn-outline w-full mt-4"
            @click.prevent="voteOption(option.id)"
            type="button"
          >
            {{ option.text }}
          </button>
          <div v-if="voted" :key="index">
            {{ option.text }}<br />
            <progress-bar
              :options="progressBarOptions"
              :value="Math.round((100 / total) * option.sum)"
            />
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

    const progressBarOptions = {
      text: {
        color: textColor,
        shadowEnable: false,
        shadowColor: '#000000',
        fontSize: 14,
        fontFamily: 'Helvetica',
        dynamicPosition: false,
        hideText: false,
      },
      progress: {
        color: props.campaignColor,
        backgroundColor: '#2e2e2e',
        inverted: true,
      },
      layout: {
        height: 35,
        width: 200,
        verticalTextAlign: 61,
        horizontalTextAlign: 43,
        zeroOffset: 0,
        strokeWidth: 30,
        progressPadding: 0,
        type: 'line',
      },
    }

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
      progressBarOptions,
    }
  },
})
</script>

<template>
  <div>
    <p class="text-lg font-bold mb-2">{{ post.question }}</p>
    <p>{{ post.body }}</p>

    <div class="poll-options mt-4">
      <div v-for="(option, index) of post.pollOptions" :key="index">
        <div class="flex flex-row items-center mb-4">
          <span
            class="bg-$highlight text-$highlight-contrast w-10 h-10 mr-2 rounded-full flex flex-row justify-center items-center"
          >
            {{ $t('post.types.poll.shortOption.' + index) }}
          </span>
          <span
            :id="'poll-option-' + option.id"
            class="poll-option"
            @click.prevent="vote(option.id)"
          >
            {{ option.title }}
          </span>
        </div>
      </div>
    </div>
  </div>
</template>
<script lang="ts">
import { defineComponent } from '@nuxtjs/composition-api'
import { postApi } from '@/api/post'

export default defineComponent({
  props: {
    post: {
      type: Object,
      required: true,
    },
  },
  setup() {
    const { votePollOption } = postApi()

    function vote(optionId: any) {
      votePollOption(optionId)
    }

    return {
      vote,
    }
  },
})
</script>

<template>
  <div>
    <p class="text-lg font-bold mb-2">{{ post.question }}</p>
    <p>{{ post.body }}</p>

    <div class="poll-options mt-4 grid grid-cols-2 gap-4">
      <div v-for="(option, key) of post.pollOptions" :key="key">
        <div class="flex flex-row items-center mb-4 cursor-pointer" @click.prevent="vote(option.id)">
          <span
            class="bg-$highlight text-$highlight-contrast w-10 h-10 mr-2 rounded-full flex flex-row justify-center items-center flex-shrink-0 flex-grow-0 mr-4"
          >
            {{ $t('post.types.poll.shortOption.' + key) }}
          </span>
          <span
            :id="'poll-option-' + option.id"
            class="poll-option"
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

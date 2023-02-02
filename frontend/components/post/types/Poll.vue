<template>
  <div>
    <p class="text-lg font-bold mb-2">{{ post.question }}</p>
    <p class="break-words">{{ post.body }}</p>

    <div
      v-if="!post.userChoiced && (post.campaign.active || !post.campaign.closed)"
      class="poll-options mt-4 grid lg:grid-cols-2 gap-4"
    >
      <div v-for="(option, key) of post.pollOptions" :key="key">
        <button
          class="flex flex-row items-center cursor-pointer w-full text-left"
          @click.prevent="vote(option.id)"
        >
          <span
            class="bg-$highlight text-$highlight-contrast w-10 h-10 mr-2 rounded-full flex flex-row justify-center items-center flex-shrink-0 flex-grow-0 mr-4"
          >
            {{ $t('post.types.poll.shortOption.' + key) }}
          </span>
          <span :id="'poll-option-' + option.id" class="poll-option">
            {{ option.title }}
          </span>
        </button>
      </div>
    </div>
    <div v-else>
      <template v-for="(option, key) in post.pollOptions">
        <div :key="key" class="mb-6 mt-6">
          <div class="mb-2">{{ option.title }}</div>
          <div class="box-shadow-inset rounded-xl">
            <div
              class="bg-$highlight rounded-xl text-$highlight-contrast text-center"
              :style="`width: ${Math.round(
                (100 / post.choicesTotal) * option.votes
              )}%`"
            >
              <span
                class="px-3 py-0.5 inline-block"
                :class="
                  Math.round((100 / post.choicesTotal) * option.votes) < 10
                    ? 'text-white'
                    : ''
                "
                >{{
                  Math.round((100 / post.choicesTotal) * option.votes)
                }}%</span
              >
            </div>
          </div>
        </div>
      </template>
    </div>
  </div>
</template>
<script lang="ts">
import { defineComponent, useContext, useStore } from '@nuxtjs/composition-api'
import { postApi } from '@/api/post'

export default defineComponent({
  props: {
    post: {
      type: Object,
      required: true,
    },
  },
  emits: ['updatePost'],
  setup(_, context) {
    const { votePollOption } = postApi()
    const { $auth } = useContext()
    const store = useStore()

    async function vote(optionId: any) {
      if (!$auth.loggedIn) {
        store.dispatch('showLogin')
        return
      }
      await votePollOption(optionId).then(function () {
        context.emit('updatePost')
      })
    }

    return {
      vote,
    }
  },
})
</script>

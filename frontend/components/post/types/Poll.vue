<template>
  <div>
    <span class="poll-title">{{ post.question }}</span>
    <br>
    <span class="poll-desc">{{ post.body }}</span>

    <div class="poll-options">
      <div v-for="option of post.pollOptions">
        <span class="poll-option" :id="'poll-option-' + option.id" @click.prevent="vote(option.id)">
          {{ option.title }}
        </span>
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
      required: true
    }
  },
  setup() {

    const { votePollOption } = postApi()

    function vote(optionId:any) {
      votePollOption(optionId)
    }

    return {
      vote
    }
  },
})
</script>

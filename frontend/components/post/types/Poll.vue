<template>
  <div>
    <p w:text="lg" w:font="bold" w:mb="2">{{ post.question }}</p>
    <p>{{ post.body }}</p>

    <div class="poll-options" w:mt="4">
      <div v-for="option of post.pollOptions">
        <div w:flex="~ row" w:align="items-center" w:mb="4">
          <span w:w="10" w:h="10" w:mr="2" w:border="rounded-full" w:flex="~ row" w:justify="center" w:align="items-center" class="highlight-bg">
            {{ option.id }}
          </span>
          <span class="poll-option" :id="'poll-option-' + option.id" @click.prevent="vote(option.id)">
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

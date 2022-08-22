<template>
  <div class="flex flex-col flex-1 h-full">
    <div class="page-header p-6">
      <button class="back-btn" @click.prevent="abortPost">
        {{ $t('post.types.text.headline') }}
      </button>
    </div>
    <div
      class="flex flex-col flex-1 h-full justify-between pr-6 pb-6 pl-6"
    >
      <div class="relative">
        <input
          v-model="postTitle"
          type="text"
          class="input-text pr-20"
          :placeholder="$t('post.placeholder.title')"
          :maxlength="100"
        />
        <CountDown
          :max-count="100"
          :text="postTitle"
          class="absolute bottom-1 right-2"
        />
      </div>

      <div class="flex flex-col flex-grow mt-4 relative">
        <textarea
          v-model="postBody"
          type="text"
          class="input-text flex-grow pr-21"
          :maxlength="1000"
        ></textarea>
        <CountDown
          :max-count="1000"
          :text="postBody"
          class="absolute bottom-1 right-2"
        />
      </div>
      <button
        type="submit"
        class="btn-primary mt-6 w-full"
        @click.prevent="submitPost"
      >
        {{ $t('post.share') }}
      </button>
    </div>
  </div>
</template>
<script>
import {
  defineComponent,
  ref,
  useContext
} from '@nuxtjs/composition-api'
import { postApi } from '@/api/post'

export default defineComponent({
  emits: ['abortPost', 'closeAddModal'],
  setup(_, context) {
    const { store } = useContext()

    const postTitle = ref('')

    const postBody = ref('')

    const { createTextPost } = postApi()

    function submitPost() {
      createTextPost(
        store.state.currentCampaign,
        postTitle.value,
        postBody.value
      ).then(function () {
        postTitle.value = ''
        postBody.value = ''
        context.emit('closeAddModal')
        store.dispatch('setNewPostOnCampaign', store.state.currentCampaign)
      })
    }

    function abortPost() {
      context.emit('abortPost')
    }

    return {
      postTitle,
      postBody,
      abortPost,
      submitPost,
    }
  },
})
</script>

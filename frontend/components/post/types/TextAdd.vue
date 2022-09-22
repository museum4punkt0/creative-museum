<template>
  <div class="flex flex-col flex-1 h-full">
    <div class="page-header px-6">
      <button class="back-btn" type="button" @click.prevent="abortPost">
        {{ $t('post.types.text.headline') }}
      </button>
    </div>
    <div
      class="flex flex-col flex-1 h-full justify-between pr-6 pb-18 md:pb-6 pl-6"
    >
      <div class="relative">
        <input
          v-model="postTitle"
          type="text"
          class="input-text pr-20"
          :placeholder="$t('post.placeholder.title')"
          :maxlength="100"
        />
        <UtilitiesCountDown
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
        <UtilitiesCountDown
          :max-count="1000"
          :text="postBody"
          class="absolute bottom-1 right-2"
        />
      </div>
      <button
        type="submit"
        :disabled="disableSubmitButton"
        class="btn-highlight disabled:opacity-30 mt-6 w-full"
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
  useContext,
  computed,
} from '@nuxtjs/composition-api'
import { postApi } from '@/api/post'

export default defineComponent({
  emits: ['abortPost', 'closeAddModal'],
  setup(_, context) {
    const { store } = useContext()

    const postTitle = ref('')
    const postBody = ref('')
    const submitting = ref(false)

    const disableSubmitButton = computed(() => {
      return postBody.value.length === 0 || submitting.value
    })

    const { createTextPost } = postApi()

    function submitPost() {
      submitting.value = true

      createTextPost(
        store.state.currentCampaign,
        postTitle.value,
        postBody.value
      ).then(function () {
        postTitle.value = ''
        postBody.value = ''
        submitting.value = false
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
      disableSubmitButton,
      submitting,
      abortPost,
      submitPost,
    }
  },
})
</script>

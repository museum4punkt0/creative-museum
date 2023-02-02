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

      <div class="flex flex-col flex-grow mt-4 pb-4 relative">
        <UtilitiesRichTextEditor
          v-model="postBody"
          :placeholder="$t('post.placeholder.body')" class="input-text flex-grow"
          @update:modelValue="updateModelValue"/>
        <UtilitiesCountDownRichText
          :max-count="1000"
          :count="postBodyLength"
          class="absolute bottom-5 right-2"
        />
      </div>

      <button
        type="submit"
        :disabled="disableSubmitButton"
        class="btn-highlight disabled:opacity-30 w-full"
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
    const { store, i18n } = useContext()

    const postTitle = ref('')
    const postBody = ref('')
    const postBodyLength = ref(0)
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
        store.dispatch('currentAlert', i18n.t('alert.postSubmitted'))
      })
    }

    function abortPost() {
      context.emit('abortPost')
    }

    function updateModelValue(content) {
      postBody.value = content.text
      postBodyLength.value = content.count
    }

    return {
      postTitle,
      postBody,
      postBodyLength,
      disableSubmitButton,
      submitting,
      abortPost,
      submitPost,
      updateModelValue
    }
  },
})
</script>

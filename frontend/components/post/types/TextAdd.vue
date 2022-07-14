<template>
  <div w:flex="~ col 1" w:h="full">
    <div w:p="6" class="page-header">
      <button class="back-btn" @click.prevent="abortPost">{{ $t('post.types.text.headline') }}</button>
    </div>
    <div
      w:flex="~ col" w:justify="space-between"
    >
      <div>
        <textarea v-model="postBody" type="text" class="input-text"></textarea>
      </div>
      <div

      >
        <button type="submit" class="btn-primary" @click.prevent="submitPost">{{ $t('post.share') }}</button>
      </div>
    </div>
  </div>
</template>
<script>
import { defineComponent, ref, useContext, useRouter } from '@nuxtjs/composition-api'
import { postApi } from '@/api/post'

export default defineComponent({
  emits: [
    'abortPost',
    'closeAddModal'
  ],
  setup(_, context) {

    const { store } = useContext()
    const router = useRouter()

    const postBody = ref('')

    const { createTextPost } = postApi()

    function submitPost() {
      createTextPost( store.state.currentCampaign, postBody.value ).then(function() {
        postBody.value = ''
        context.emit('closeAddModal')
        store.dispatch('setNewPostOnCampaign', store.state.currentCampaign)
      })
    }

    function abortPost() {
      context.emit('abortPost')
    }

    return {
      postBody,
      abortPost,
      submitPost
    }
  },
})
</script>

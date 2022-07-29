<template>
  <div w:flex="~ col 1" w:h="full">
    <div w:p="6" class="page-header">
      <button class="back-btn" @click.prevent="abortPost">{{ $t('post.types.text.headline') }}</button>
    </div>
    <div
      w:flex="~ col 1" w:h="full" w:justify="between" w:pr="6" w:pb="6" w:pl="6"
    >
      <div w:flex="~ col grow">
        <textarea v-model="postBody" type="text" class="input-text" w:flex="grow" :maxlength="maxCount"></textarea>
        <countdown :max-count="maxCount" :text="postBody" />
      </div>
      <button type="submit" class="btn-primary" w:mt="6" w:w="full" @click.prevent="submitPost">{{ $t('post.share') }}</button>
    </div>
  </div>
</template>
<script>
import { defineComponent, ref, useContext, useRouter } from '@nuxtjs/composition-api'
import { postApi } from '@/api/post'
import Countdown from "~/components/Countdown";

export default defineComponent({
  emits: [
    'abortPost',
    'closeAddModal'
  ],
  components: {
    Countdown
  },
  setup(_, context) {

    const { store } = useContext()

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
      submitPost,
    }
  },
  data() {
    return {
      maxCount: 1000,
      hasError: false
    }
  }
})
</script>

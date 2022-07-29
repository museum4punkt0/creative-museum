<template>
  <div>
    <div w:p="6" class="page-header">
      <button class="back-btn" @click.prevent="abortPost">{{ $t('post.types.poll.headline') }}</button>
    </div>

    <div w:flex="~ col" w:justify="space-between">
      <div>
        <input type="text" v-model="question" class="input-text" :placeholder="$t('post.types.poll.pollTitle')" />
      </div>

      <div>
        <input type="text" v-model="description" class="input-text" :placeholder="$t('post.types.poll.pollDescription')" />
      </div>

      <div>
        <div v-for="(option, index) of options">
          <input type="text" class="input-text" v-model="option.value" :placeholder="$t('post.types.poll.option.' + index)" />
          <button class="btn-borderless" @click.prevent="removeOption(index)" v-if="options.length > 2">
            {{ $t('post.types.poll.removeOption') }}
          </button>
        </div>

        <div v-if="options.length < 5">
          <button class="btn-primary" @click.prevent="addOption">{{ $t('post.types.poll.addOption') }}</button>
        </div>
      </div>

      <div>
        <button type="submit" class="btn-primary" @click.prevent="submitPost">{{ $t('post.share') }}</button>
      </div>
    </div>

  </div>
</template>
<script lang="ts">
import {defineComponent, ref, computed, useContext} from '@nuxtjs/composition-api'
import { postApi } from '@/api/post'

export default defineComponent({
  emits: [
    'abortPost'
  ],
  setup(_, context) {

    const { createPollPost } = postApi()
    const { store } = useContext()

    const initialOptions = [{value: ''}, {value: ''}]
    const question = ref('')
    const description = ref('')

    const options = ref(initialOptions)

    const optionCount = computed(() => options.value.length)

    function addOption() {
      if (options.value.length < 5) {
        options.value.push({value: ''})
      }
    }

    function removeOption(index:any) {
      options.value.splice(index, 1)
    }

    function abortPost() {
      context.emit('abortPost')
    }
    function submitPost() {

      const postData = {
        question: question.value,
        description: description.value,
        options: options.value
      }

      createPollPost( store.state.currentCampaign, postData ).then(function() {
        question.value = ''
        description.value = ''
        options.value = initialOptions

        context.emit('closeAddModal')
        store.dispatch('setNewPostOnCampaign', store.state.currentCampaign)
      })
    }

    return {
      abortPost,
      submitPost,
      question,
      description,
      options,
      optionCount,
      addOption,
      removeOption
    }
  },
})
</script>

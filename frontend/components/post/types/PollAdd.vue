<template>
  <div w:flex="~ col 1" w:h="full">
    <div w:p="6" class="page-header">
      <button class="back-btn" @click.prevent="abortPost">{{ $t('post.types.poll.headline') }}</button>
    </div>

    <div w:flex="~ col 1" w:h="full" w:justify="between" w:pr="6" w:pb="6" w:pl="6">
      <div w:flex="~ col grow">
        <div w:position="relative">
          <input type="text" v-model="question" class="input-text" w:pr="20" :placeholder="$t('post.types.poll.pollTitle')" :maxlength="100" />
          <countdown :max-count="100" :text="question" w:position="absolute" w:bottom="1" w:right="2" />
        </div>
        <div w:position="relative" w:mt="4" w:min-h="1/5">
          <textarea type="text" v-model="description" class="input-text" w:h="full" w:pr="20" :placeholder="$t('post.types.poll.pollDescription')" :maxlength="100" />
          <countdown :max-count="100" :text="description" w:position="absolute" w:bottom="1" w:right="2" />
        </div>
        <div v-for="(option, index) of options" w:mt="4">
          <label w:display="block" w:pb="1">{{$t('post.types.poll.option.' + index)}}</label>
          <div w:flex="~ row" w:justify="between" w:align="items-center">
            <div w:position="relative" w:flex="1">
              <input v-model="option.value" type="text" class="input-text" w:pr="20" :maxlength="100" />
              <countdown :max-count="100" :text="option.value" w:position="absolute" w:bottom="1" w:right="2" />
            </div>
            <svg v-if="options.length > 2" w:w="6" w:h="6" w:ml="4" viewBox="0 0 26 25" fill="none" xmlns="http://www.w3.org/2000/svg" :aria-label="$t('post.types.poll.removeOption')" @click.prevent="removeOption(index)">
              <path d="M13 24.5C19.6274 24.5 25 19.1274 25 12.5C25 5.87258 19.6274 0.5 13 0.5C6.37258 0.5 1 5.87258 1 12.5C1 19.1274 6.37258 24.5 13 24.5Z" fill="#2E2E2E" stroke="white" stroke-miterlimit="10" stroke-linecap="round"/>
              <path d="M5.2207 12.0723H20.7811" stroke="white" stroke-miterlimit="10" stroke-linecap="round"/>
            </svg>
          </div>
        </div>
        <div v-if="options.length < 5">
          <div w:flex="~ col" w:align="items-center" w:mt="6">
            <svg w:w="6" w:h="6" @click.prevent="addOption" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" :aria-label="$t('post.types.poll.addOption')">
              <path d="M12 22C17.5228 22 22 17.5228 22 12C22 6.47715 17.5228 2 12 2C6.47715 2 2 6.47715 2 12C2 17.5228 6.47715 22 12 22Z" stroke="#FFFFFF" stroke-miterlimit="10" stroke-linecap="round"/>
              <path d="M12 5.28564V18.7142" stroke="#FFFFFF" stroke-miterlimit="10" stroke-linecap="round"/>
              <path d="M18.7143 11.8513H5.28571" stroke="#FFFFFF" stroke-miterlimit="10" stroke-linecap="round"/>
            </svg>
          </div>
        </div>
      </div>
      <div w:mt="6">
        <button type="submit" class="btn-primary" w:w="full" @click.prevent="submitPost">{{ $t('post.share') }}</button>
      </div>

      <div>
      </div>
    </div>

  </div>
</template>
<script>
import {defineComponent, ref, computed, useContext} from '@nuxtjs/composition-api'
import { postApi } from '@/api/post'
import Countdown from '@/components/Countdown';

export default defineComponent({
  emits: [
    'abortPost'
  ],
  components: {
    Countdown
  },
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

    function removeOption(index) {
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

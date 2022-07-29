<template>
  <div w:flex="~ col 1" w:h="full">
    <div w:p="6" class="page-header">
      <button class="back-btn" @click.prevent="abortPost">{{ $t('post.types.image.headline') }}</button>
    </div>
    <div w:flex="~ col 1" w:h="full" w:justify="between" w:pr="6" w:pb="6" w:pl="6">
      <client-only>

        <img v-if="files.length" :src="files[0].blob" w:max-h="1/3 lg:48" w:w="auto" w:border="rounded" w:align="self-center" />

        <file-upload ref="upload"
          v-model="files"
          accept="image/png,image/gif,image/jpeg"
          class="block"
          @input-file="inputFile"
          @input-filter="inputFilter"
        >
          <div class="box-shadow-inset" w:pt="2" w:pr="2" w:pb="10" w:pl="2" w:border="rounded-xl" w:text="left" w:flex="~ row">
            {{ $t('post.types.image.uploader.' + (files.length ? 'replace' : 'add')) }}
            <svg w:w="6" w:h="6" w:ml="2" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
              <path d="M12 22C17.5228 22 22 17.5228 22 12C22 6.47715 17.5228 2 12 2C6.47715 2 2 6.47715 2 12C2 17.5228 6.47715 22 12 22Z" stroke="#FFFFFF" stroke-miterlimit="10" stroke-linecap="round"/>
              <path d="M12 5.28571V18.7143" stroke="#FFFFFF" stroke-miterlimit="10" stroke-linecap="round"/>
              <path d="M18.7137 11.8514H5.28516" stroke="#FFFFFF" stroke-miterlimit="10" stroke-linecap="round"/>
            </svg>
          </div>
        </file-upload>
      </client-only>
      <div w:flex="~ col grow">
        <textarea v-model="postBody" type="text" class="input-text" w:mt="6" w:flex="grow" :maxlength="maxCount" @keyup="countdown"></textarea>
        <p w:text="right" w:mt="3" w:mr="3" class='highlight-text' :class="{'text-danger': hasError }">{{remainingCount}} / {{maxCount}}</p>
      </div>
      <button type="submit" class="btn-primary" w:mt="6" w:w="full" @click.prevent="submitPost">{{ $t('post.share') }}</button>

    </div>
  </div>
</template>
<script lang="ts">
import { defineComponent, ref, useAsync, useContext } from '@nuxtjs/composition-api'
import {postApi} from "~/api/post";

export default defineComponent({
  components: {
    FileUpload: () => import('vue-upload-component')
  },
  emits: [
    'abortPost'
  ],
  setup(_, context) {

    const { store } = useContext()

    const files = ref([])
    const edit = ref(false)
    const postBody = ref('')
    const { createPicturePost } = postApi()

    function abortPost() {
      context.emit('abortPost')
    }

    function submitPost() {
      createPicturePost( store.state.currentCampaign, postBody.value, files.value ).then(function() {
        postBody.value = ''
        files.value = []
        context.emit('closeAddModal')
        store.dispatch('setNewPostOnCampaign', store.state.currentCampaign)
      })
    }

    function inputFile(newFile:any, oldFile:any, prevent:any) {
      if (newFile && (!oldFile || newFile.file !== oldFile.file)) {
        newFile.blob = ''
        let URL = window.URL || window.webkitURL
        if (URL && URL.createObjectURL) {
          newFile.blob = URL.createObjectURL(newFile.file)
        }
      }
      if (!newFile && oldFile) {
        files.value = []
      }
    }

    function inputFilter(newFile:any, oldFile:any, prevent:any) {
      console.log('inputFilter')
      if (newFile) {
        if (!/\.(gif|jpg|jpeg|png|webp)$/i.test(newFile.name)) {
          console.log('Your choice is not a picture')
          return prevent()
        }
      }
    }

    return {
      abortPost,
      submitPost,
      files,
      inputFile,
      inputFilter,
      edit,
      postBody
    }
  },
  data() {
    return {
      maxCount: 1000,
      remainingCount: 1000,
      hasError: false
    }
  },
  methods: {
    countdown: function() {
      this.remainingCount = this.maxCount - this.postBody.length;
      this.hasError = this.remainingCount < 0;
    }
  }
})
</script>

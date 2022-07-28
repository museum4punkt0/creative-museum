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
          <div class="btn-outline" w:px="4" w:py="2" w:mt="6">
            {{ $t('post.types.image.uploader.' + (files.length ? 'replace' : 'add')) }}
          </div>
        </file-upload>
      </client-only>
      <textarea v-model="postBody" type="text" class="input-text" w:mt="6" w:flex="grow"></textarea>
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
})
</script>

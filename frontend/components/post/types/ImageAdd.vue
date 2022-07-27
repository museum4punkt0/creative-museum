<template>
  <div>
    <div w:p="6" class="page-header">
      <button class="back-btn" @click.prevent="abortPost">{{ $t('post.types.image.headline') }}</button>
    </div>
    <div w:p="6" class="page-body">
      <client-only>

        <img v-if="files.length" :src="files[0].blob" />

        <file-upload ref="upload"
          v-model="files"
          accept="image/png,image/gif,image/jpeg"
          class="block"
          @input-file="inputFile"
          @input-filter="inputFilter"
        >
          <div style="width:100%;min-height:5vh;background:#333">
            {{ $t('post.types.image.uploader.' + (files.length ? 'replace' : 'add')) }}
          </div>
        </file-upload>
      </client-only>

      <div>
        <textarea v-model="postBody" type="text" class="input-text"></textarea>
      </div>

      <button type="submit" class="btn-primary" @click.prevent="submitPost">{{ $t('post.share') }}</button>

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

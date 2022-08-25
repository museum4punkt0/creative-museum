<template>
  <div>
    <div class="page-header p-6">
      <button class="back-btn" @click.prevent="abortPost" type="button">
        {{ $t('post.types.audio.headline') }}
      </button>
    </div>
    <div
      class="flex flex-col flex-1 h-full justify-between pr-6 pb-6 pl-6"
    >
      <div class="box-shadow mb-6">
        <UtilitiesAudioRecorder @audioFile="inputAudioFile" />
      </div>
      <div class="relative">
        <input
          v-model="postTitle"
          type="text"
          class="input-text pr-20 mb-4"
          :placeholder="$t('post.placeholder.title')"
          :maxlength="100"
        />
      </div>
      <client-only>
        <img
          v-if="images.length"
          :src="images[0].blob"
          class="max-h-1/3 lg:max-h-48 w-auto rounded self-center"
        />
        <file-upload
          ref="upload"
          v-model="images"
          accept="image/png,image/gif,image/jpeg"
          class="block"
          @input-file="inputFile"
          @input-filter="inputFilter"
        >
          <div
            class="box-shadow-inset pt-2 pr-2 pb-10 pl-2 rounded-xl text-left flex flex-row"
          >
            {{
              $t(
                'post.types.image.uploader.' +
                  (images.length ? 'replace' : 'add')
              )
            }}
            <svg
              class="w-6 h-6 ml-2"
              viewBox="0 0 24 24"
              fill="none"
              xmlns="http://www.w3.org/2000/svg"
            >
              <path
                d="M12 22C17.5228 22 22 17.5228 22 12C22 6.47715 17.5228 2 12 2C6.47715 2 2 6.47715 2 12C2 17.5228 6.47715 22 12 22Z"
                stroke="#FFFFFF"
                stroke-miterlimit="10"
                stroke-linecap="round"
              />
              <path
                d="M12 5.28571V18.7143"
                stroke="#FFFFFF"
                stroke-miterlimit="10"
                stroke-linecap="round"
              />
              <path
                d="M18.7137 11.8514H5.28516"
                stroke="#FFFFFF"
                stroke-miterlimit="10"
                stroke-linecap="round"
              />
            </svg>
          </div>
        </file-upload>
      </client-only>
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
<script lang="ts">
import { defineComponent, ref, useContext } from '@nuxtjs/composition-api'
import { postApi } from '@/api/post'

export default defineComponent({
  components: {
    FileUpload: () => import('vue-upload-component'),
  },
  emits: ['abortPost', 'closeAddModal'],
  setup(_, context) {

    const { store } = useContext()
    const postTitle = ref('')
    let fileToSubmit: any | null = null

    const images = ref([])

    const { createAudioPost } = postApi()

    function inputAudioFile(audioFile: any) {
      fileToSubmit = audioFile
    }

    function abortPost() {
      context.emit('abortPost')
    }

    function inputFile( newFile: any, oldFile: any ) {
      if (newFile && (!oldFile || newFile.file !== oldFile.file)) {
        newFile.blob = ''
        const URL = window.URL || window.webkitURL
        if (URL && URL.createObjectURL) {
          newFile.blob = URL.createObjectURL(newFile.file)
        }
      }
      if (!newFile && oldFile) {
        images.value = []
      }
    }

    function inputFilter( newFile: any ) {
      console.log('inputFilter')
      if (newFile) {
        if (!/\.(gif|jpg|jpeg|png|webp)$/i.test(newFile.name)) {
          console.log('Your choice is not a picture')
        }
      }
    }

    function submitPost() {

      const pictureArray = images.value

      createAudioPost(
        store.state.currentCampaign,
        postTitle.value,
        fileToSubmit,
        pictureArray[0]
      ).then(function () {
        postTitle.value = ''
        fileToSubmit.value = null
        images.value = []
        context.emit('closeAddModal')
        store.dispatch('setNewPostOnCampaign', store.state.currentCampaign)
      })
    }

    return {
      postTitle,
      images,
      fileToSubmit,
      inputAudioFile,
      inputFile,
      inputFilter,
      abortPost,
      submitPost,
    }
  },
})
</script>

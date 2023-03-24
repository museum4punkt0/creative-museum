<template>
  <div class="flex flex-col flex-1 h-full">
    <div class="page-header px-6">
      <button class="back-btn" @click.prevent="abortPost">
        {{ $t('post.types.audio.headline') }}
      </button>
    </div>
    <div
      class="flex flex-col flex-1 h-full justify-between pr-6 pb-18 md:pb-6 pl-6"
    >
      <client-only>
        <NoteIcon
          v-if="fileToSubmit"
          class="h-30 w-30 max-h-1/3 lg:max-h-48 mb-4 w-auto rounded self-center"
        />
        <file-upload
          v-if="!(fileToSubmit && uploadedAudio.length === 0)"
          ref="uploadAudio"
          v-model="uploadedAudio"
          input-id="file1"
          accept="audio/*"
          aria-required="true"
          extensions="wav,mp3,ogg"
          @input-file="inputUploadedAudioFile"
          @input-filter="inputAudioFilter"
        >
          <div
            class="box-shadow-inset pt-2 pr-2 pb-10 pl-4 rounded-xl text-left flex flex-row"
          >
            {{
              $t(
                'post.types.audio.uploader.' +
                  (fileToSubmit ? 'replace' : 'add')
              )  + ' *'
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
        <div v-if="uploadedAudio.length === 0">
          <h3 v-if="!fileToSubmit" class="mb-4">{{ $t('post.types.audio.mode')}}</h3>
          <div class="box-shadow mb-6">
            <UtilitiesAudioRecorder @audioFile="inputAudioFile" />
          </div>
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
        <img
          v-if="images.length"
          :src="images[0].blob"
          class="max-h-1/3 lg:max-h-48 w-auto rounded self-center"
        />
        <file-upload
          ref="uploadImage"
          v-model="images"
          input-id="file2"
          accept="image/png,image/gif,image/jpeg"
          class="block"
          extensions="jpg,jpeg png,bmp,gif"
          @input-file="inputFile"
          @input-filter="inputFilter"
        >
          <div
            class="box-shadow-inset pt-2 pr-2 pb-10 pl-4 rounded-xl text-left flex flex-row"
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
      <div>
        <button
          v-if="fileToSubmit"
          class="btn-outline w-full"
          @click.prevent="triggerInput"
        >{{ $t('post.types.audio.uploader.replace') }}</button>
        <button
          type="submit"
          class="btn-highlight disabled:opacity-30 w-full mb-12 md:mb-0"
          :class="fileToSubmit ? 'mt-4' : ''"
          :disabled="disableSubmitButton"
          @click.prevent="submitPost"
        >
          {{ $t('post.share') }}
        </button>
      </div>
    </div>
  </div>
</template>
<script lang="ts">
import {
  defineComponent,
  ref,
  useContext,
  computed,
} from '@nuxtjs/composition-api'
import { postApi } from '@/api/post'
import NoteIcon from '@/assets/icons/note.svg?inline'

export default defineComponent({
  components: {
    NoteIcon,
    FileUpload: () => import('vue-upload-component'),
  },
  emits: ['abortPost', 'closeAddModal'],
  setup(_, context) {
    const { store, i18n } = useContext()
    const postTitle = ref('')
    const fileToSubmit: any | null = ref(null)
    const uploadedAudio = ref([])
    const images = ref([])
    const submitting = ref(false)

    const disableSubmitButton = computed(() => {
      return fileToSubmit.value === null || submitting.value
    })

    const { createAudioPost } = postApi()

    function inputAudioFile(audioFile: any) {
      fileToSubmit.value = audioFile
    }

    function inputUploadedAudioFile(newFile: any, oldFile: any) {
      if (newFile && (!oldFile || newFile.file !== oldFile.file)) {
        newFile.blob = ''
        const URL = window.URL || window.webkitURL
        if (URL && URL.createObjectURL) {
          newFile.blob = URL.createObjectURL(newFile.file)
        }
      }
      if (!newFile && oldFile) {
        uploadedAudio.value = []
      }
      fileToSubmit.value = newFile
    }

    function triggerInput() {
      if (process.client) {
        const fileInput = window.document.getElementById('file1')
        if (fileInput !== null) {
          fileInput.click()
        }
      }
    }

    function inputFile(newFile: any, oldFile: any) {
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

    function inputAudioFilter(newFile: any) {
      console.log('inputFilter')
      if (newFile) {
        if (!/\.(wav|mp3|ogg)$/i.test(newFile.name)) {
          console.log('Your choice is not a audio file')
        }
      }
    }

    function inputFilter(newFile: any) {
      console.log('inputFilter')
      if (newFile) {
        if (!/\.(gif|jpg|jpeg|png|webp)$/i.test(newFile.name)) {
          console.log('Your choice is not a picture')
        }
      }
    }

    function abortPost() {
      context.emit('abortPost')
    }

    function submitPost() {
      const pictureArray = images.value

      submitting.value = true

      createAudioPost(
        store.state.currentCampaign,
        postTitle.value,
        fileToSubmit.value,
        pictureArray[0]
      ).then(function () {
        postTitle.value = ''
        fileToSubmit.value = null
        images.value = []
        submitting.value = false
        context.emit('closeAddModal')
        store.dispatch('setNewPostOnCampaign', store.state.currentCampaign)
        store.dispatch('currentAlert', i18n.t('alert.postSubmitted'))
      })
    }

    return {
      postTitle,
      images,
      uploadedAudio,
      fileToSubmit,
      submitting,
      disableSubmitButton,
      triggerInput,
      inputAudioFile,
      inputFile,
      inputUploadedAudioFile,
      inputAudioFilter,
      inputFilter,
      abortPost,
      submitPost,
    }
  },
})
</script>

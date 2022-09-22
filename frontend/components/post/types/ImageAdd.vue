<template>
  <div class="flex flex-col flex-1 h-full">
    <div class="page-header px-6">
      <button class="back-btn" type="button" @click.prevent="abortPost">
        {{ $t('post.types.image.headline') }}
      </button>
    </div>
    <div class="flex flex-col flex-1 h-full justify-between pr-6 pb-6 pl-6">
      <client-only>
        <img
          v-if="files.length"
          :src="files[0].blob"
          class="max-h-1/3 lg:max-h-48 w-auto rounded self-center"
        />

        <file-upload
          ref="upload"
          v-model="files"
          accept="image/png,image/gif,image/jpeg"
          class="block"
          @input-file="inputFile"
          @input-filter="inputFilter"
        >
          <div
            class="box-shadow-inset pt-2 pr-2 pb-10 pl-4 rounded-xl text-left flex flex-row"
          >
            {{
              $t(
                'post.types.image.uploader.' +
                  (files.length ? 'replace' : 'add')
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

      <div class="relative mt-4">
        <input
          v-model="imgAlt"
          type="text"
          class="input-text pr-21"
          :placeholder="$t('post.placeholder.image.alttext')"
          :maxlength="200"
        />
        <UtilitiesCountDown
          :max-count="200"
          :text="imgAlt"
          class="absolute bottom-1 right-2"
        />
      </div>

      <div class="relative mt-4">
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

      <div class="flex flex-col flex-grow relative mt-4">
        <textarea
          v-model="postBody"
          type="text"
          class="input-text flex-grow pr-21"
          :maxlength="1000"
        ></textarea>
        <UtilitiesCountDown
          :max-count="1000"
          :text="postBody"
          class="absolute bottom-1 right-2"
        />
      </div>
      <button
        ref="submitButton"
        type="submit"
        :disabled="disableSubmitButton"
        class="btn-highlight disabled:opacity-30 mt-6 w-full mb-12 md:mb-0"
        @click.prevent="submitPost"
      >
        {{ $t('post.share') }}
      </button>
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
import { postApi } from '~/api/post'

export default defineComponent({
  components: {
    FileUpload: () => import('vue-upload-component'),
  },
  emits: ['abortPost', 'closeAddModal'],
  setup(_, context) {
    const { store } = useContext()

    const files = ref([])
    const edit = ref(false)
    const postTitle = ref('')
    const postBody = ref('')
    const imgAlt = ref('')
    const submitting = ref(false)
    const { createImagePost } = postApi()

    const disableSubmitButton = computed(() => {
      return (
        files.value.length === 0 ||
        postBody.value.length === 0 ||
        submitting.value
      )
    })

    function abortPost() {
      context.emit('abortPost')
    }

    function submitPost() {
      const pictureArray = files.value

      submitting.value = true

      createImagePost(
        store.state.currentCampaign,
        postTitle.value,
        postBody.value,
        pictureArray[0],
        imgAlt.value
      ).then(function () {
        postTitle.value = ''
        postBody.value = ''
        imgAlt.value = ''
        files.value = []
        submitting.value = false
        context.emit('closeAddModal')
        store.dispatch('setNewPostOnCampaign', store.state.currentCampaign)
      })
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
        files.value = []
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

    return {
      abortPost,
      submitPost,
      inputFile,
      inputFilter,
      edit,
      files,
      postTitle,
      postBody,
      imgAlt,
      disableSubmitButton,
      submitting,
    }
  },
})
</script>

<template>
  <div w:flex="~ col 1" w:h="full">
    <div w:p="6" class="page-header">
      <button class="back-btn" @click.prevent="abortPost">
        {{ $t('post.types.image.headline') }}
      </button>
    </div>
    <div
      w:flex="~ col 1"
      w:h="full"
      w:justify="between"
      w:pr="6"
      w:pb="6"
      w:pl="6"
    >
      <client-only>
        <img
          v-if="files.length"
          :src="files[0].blob"
          w:max-h="1/3 lg:48"
          w:w="auto"
          w:border="rounded"
          w:align="self-center"
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
            class="box-shadow-inset"
            w:pt="2"
            w:pr="2"
            w:pb="10"
            w:pl="2"
            w:border="rounded-xl"
            w:text="left"
            w:flex="~ row"
          >
            {{
              $t(
                'post.types.image.uploader.' +
                  (files.length ? 'replace' : 'add')
              )
            }}
            <svg
              w:w="6"
              w:h="6"
              w:ml="2"
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

      <div w:position="relative" w:mt="4">
        <input
          v-model="imgAlt"
          type="text"
          class="input-text"
          w:pr="21"
          :placeholder="$t('post.placeholder.image.alttext')"
          :maxlength="200"
        />
        <countdown
          :max-count="200"
          :text="imgAlt"
          w:position="absolute"
          w:bottom="1"
          w:right="2"
        />
      </div>

      <div w:position="relative" w:mt="4">
        <input
          v-model="postTitle"
          type="text"
          class="input-text"
          w:pr="20"
          :placeholder="$t('post.placeholder.title')"
          :maxlength="100"
        />
        <countdown
          :max-count="100"
          :text="postTitle"
          w:position="absolute"
          w:bottom="1"
          w:right="2"
        />
      </div>

      <div w:flex="~ col grow" w:position="relative" w:mt="4">
        <textarea
          v-model="postBody"
          type="text"
          class="input-text"
          w:flex="grow"
          w:pr="21"
          :maxlength="1000"
        ></textarea>
        <countdown
          :max-count="1000"
          :text="postBody"
          w:position="absolute"
          w:bottom="1"
          w:right="2"
        />
      </div>
      <button
        type="submit"
        class="btn-primary"
        w:mt="6"
        w:w="full"
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
  useAsync,
  useContext,
} from '@nuxtjs/composition-api'
import { postApi } from '~/api/post'
import Countdown from '~/components/Countdown.vue'

export default defineComponent({
  components: {
    Countdown,
    FileUpload: () => import('vue-upload-component'),
  },
  emits: ['abortPost'],
  setup(_, context) {
    const { store } = useContext()

    const files = ref([])
    const edit = ref(false)
    const postTitle = ref('')
    const postBody = ref('')
    const imgAlt = ref('')
    const { createPicturePost } = postApi()

    function abortPost() {
      context.emit('abortPost')
    }

    function submitPost() {
      const pictureArray = files.value

      createPicturePost(
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
        store.dispatch('setNewPostOnCampaign', store.state.currentCampaign)
      })
    }

    function inputFile(newFile: any, oldFile: any, prevent: any) {
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

    function inputFilter(newFile: any, oldFile: any, prevent: any) {
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
      inputFile,
      inputFilter,
      edit,
      files,
      postTitle,
      postBody,
      imgAlt,
    }
  },
})
</script>

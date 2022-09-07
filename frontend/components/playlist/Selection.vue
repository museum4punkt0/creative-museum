<template>
  <div class="flex flex-col flex-1">
    <div v-show="step === 1" class="flex flex-col flex-1">
      <div v-if="headline" class="page-header px-6">
        <a class="back-btn" @click="backLink">{{ $t('playlist.addTo') }}</a>
      </div>
      <div
        class="p-6 grid grid-cols-2 gap-6 mx-h-lg overflow-y-auto overscroll-y-auto"
      >
        <template v-if="'playlists' in $auth.user">
          <button
            v-for="(item, key) in $auth.user.playlists"
            :key="key"
            class="btn-primary"
            @click="$emit('selectPlaylist', item.id)"
          >
            <span class="h-30 flex flex-col align-center justify-center">
              {{ item.title }}
            </span>
          </button>
        </template>
        <button v-if="addButton" class="btn-primary" @click.prevent="step = 2">
          <span
            class="h-30 flex flex-col align-center items-center justify-center"
          >
            <span
              class="block text-center my-4 add-btn-inline"
            ></span>
            {{ $t('playlist.new') }}
          </span>
        </button>
      </div>
    </div>
    <div v-show="step === 2" class="flex flex-col flex-1 justify-between">
      <div>
        <div class="p-6 page-header">
          <a class="back-btn" @click="backLink">{{
            $t('playlist.createNew')
          }}</a>
        </div>
        <div class="p-6">
          <input
            v-model="newPlaylistName"
            class="input-text"
            :placeholder="$t('post.actions.playlist.createNew.placeholder')"
          />
        </div>
      </div>
      <div class="p-6 mt-auto">
        <button class="btn-primary w-full" @click="createPlaylistWithPost" type="button">
          {{ $t('post.actions.playlist.createNew.submitButton') }}
        </button>
      </div>
    </div>
  </div>
</template>

<script>
import {
  defineComponent,
  ref,
} from '@nuxtjs/composition-api'

export default defineComponent({
  props: {
    headline: {
      type: Boolean,
      required: false,
      default: true,
    },
    addButton: {
      type: Boolean,
      required: false,
      default: true,
    },
  },
  emits: ['closeModal', 'selectPlaylist'],
  setup(_, context) {
    const step = ref(1)

    const newPlaylistName = ref('')

    function finish() {
      context.emit('closeModal')
    }

    function backLink() {
      if (step.value > 1) {
        step.value--
      } else {
        context.emit('closeModal')
      }
    }

    function createPlaylistWithPost() {
      context.emit('createPlaylist', newPlaylistName.value)
    }

    return {
      finish,
      backLink,
      step,
      newPlaylistName,
      createPlaylistWithPost,
    }
  },
})
</script>

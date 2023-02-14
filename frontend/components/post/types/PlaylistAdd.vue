<template>
  <div class="flex flex-col flex-1 h-full">
    <div class="page-header px-6">
      <button class="back-btn" type="button" @click.prevent="abortPost">
        {{ $t('post.types.playlist.headline') }}
      </button>
    </div>
    <div
      v-if="$auth.user.playlists.length"
      class="flex flex-col flex-1 h-full justify-between pr-6 pb-12 md:pb-6 pl-6"
    >
      <div>
        <PlaylistSelection
          class="flex flex-col flex-1 items-stretch"
          :add-button="false"
          :headline="false"
          @selectPlaylist="selectPlaylist"
        />
      </div>
    </div>
    <div v-else class="p-6">
      <p class="mb-6">{{ $t('post.types.playlist.noplaylists.title') }}</p>
      <p>{{ $t('post.types.playlist.noplaylists.text') }}</p>
    </div>
  </div>
</template>
<script>
import { defineComponent, ref, useContext } from '@nuxtjs/composition-api'
import { postApi } from '@/api/post'

export default defineComponent({
  emits: ['abortPost', 'closeAddModal'],
  setup(_, context) {
    const { store, i18n } = useContext()
    const postBody = ref('')
    const { createPlaylistPost } = postApi()

    function selectPlaylist(playlistId) {
      createPlaylistPost(store.state.currentCampaign, playlistId).then(
        function () {
          context.emit('closeAddModal')
          store.dispatch('setNewPostOnCampaign', store.state.currentCampaign)
          store.dispatch('currentAlert', i18n.t('alert.postSubmitted'))
        }
      )
    }

    function abortPost() {
      context.emit('abortPost')
    }

    return {
      postBody,
      abortPost,
      selectPlaylist,
    }
  },
})
</script>

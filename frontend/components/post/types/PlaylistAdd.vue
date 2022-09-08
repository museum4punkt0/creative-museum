<template>
  <div class="flex flex-col flex-1 h-full">
    <div class="page-header px-6">
      <button class="back-btn" @click.prevent="abortPost" type="button">
        {{ $t('post.types.playlist.headline') }}
      </button>
    </div>
    <div
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
  </div>
</template>
<script>
import { defineComponent, ref, useContext } from '@nuxtjs/composition-api'
import { postApi } from '@/api/post'

export default defineComponent({
  emits: ['abortPost', 'closeAddModal'],
  setup(_, context) {
    const { store } = useContext()
    const postBody = ref('')
    const { createPlaylistPost } = postApi()

    function selectPlaylist(playlistId) {
      createPlaylistPost(store.state.currentCampaign, playlistId).then(
        function () {
          context.emit('closeAddModal')
          store.dispatch('setNewPostOnCampaign', store.state.currentCampaign)
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

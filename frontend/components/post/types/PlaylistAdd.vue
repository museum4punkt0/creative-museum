<template>
  <div w:flex="~ col 1" w:h="full">
    <div w:p="6" class="page-header">
      <button class="back-btn" @click.prevent="abortPost">
        {{ $t('post.types.playlist.headline') }}
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
      <div>
        <PlaylistSelection
          w:flex="~ col 1"
          w:align="items-stretch"
          :add-button="false"
          :headline="false"
          @closeModal=""
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

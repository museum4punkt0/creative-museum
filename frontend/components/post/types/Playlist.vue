<template>
  <div>
    <button @click.prevent="onShowPlaylist">{{ post.linkedPlaylist.title }}</button>
    <Modal v-if="showPlaylist" @closeModal="showPlaylist = false">
      <div class="flex flex-col flex-1 justify-between">
        <div>
          <div class="page-header p-6">
            <button type="button" class="back-btn" @click.prevent="showPlaylist = false">
              {{ $t('post.types.playlist.button') }}
            </button>
          </div>
          <div class="px-4 pb-4">
            <PostList v-if="playlist && playlist.posts && playlist.posts.length" :posts="playlist.posts" source="playlist" />
          </div>
        </div>
      </div>
    </Modal>
  </div>
</template>
<script lang="ts">
import { defineComponent, ref } from '@nuxtjs/composition-api'
import { playlistApi } from '@/api/playlist'

export default defineComponent({
  props: {
    post: {
      type: Object,
      required: true,
    },
  },
  setup(props) {

    const { fetchPlaylist } = playlistApi()
    const showPlaylist = ref(false)
    const playlist = ref(null)


    async function onShowPlaylist() {
      await loadPlaylist().then(function() {
        showPlaylist.value = true
      })
    }

    async function loadPlaylist() {
      playlist.value = await fetchPlaylist(props.post.linkedPlaylist.id, 1)
    }

    return {
      showPlaylist,
      playlist,
      onShowPlaylist,
      loadPlaylist
    }
  },
})
</script>

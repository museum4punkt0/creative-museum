<template>
  <div v-if="post.linkedPlaylist">
    <button
      class="text-2xl py-2 block"
      @click.prevent="!post.disableLink && onShowPlaylist()"
    >
      {{ post.linkedPlaylist.title }}
    </button>
    <UtilitiesModal v-if="showPlaylist" :aria-label="$t('modal.showPlaylist') + ' ' + post.linkedPlaylist.title"  @closeModal="showPlaylist = false">
      <div class="flex flex-col flex-1 justify-between">
        <div>
          <div class="page-header px-6">
            <button
              type="button"
              class="back-btn"
              role="button"
              @click.prevent="showPlaylist = false"
            >
              {{ $t('post.types.playlist.button') }}
            </button>
          </div>
          <div class="px-6 pb-4">
            <PostList
              v-if="playlist.posts.length"
              :posts="playlist.posts"
              source="playlist"
              class="playlist-items"
            />
          </div>
        </div>
      </div>
    </UtilitiesModal>
  </div>
</template>
<script>
import { defineComponent, ref, reactive } from '@nuxtjs/composition-api'
import { playlistApi } from '@/api/playlist'

export default defineComponent({
  props: {
    post: {
      type: Object,
      required: true,
    },
    displayMode: {
      type: String,
      default: 'Web'
    }
  },
  setup(props) {
    const { fetchPlaylist } = playlistApi()
    const showPlaylist = ref(false)
    const playlist = ref([])

    async function onShowPlaylist() {
      await loadPlaylist().then(function () {
        showPlaylist.value = true
      })
    }

    async function loadPlaylist() {
      playlist.value.posts = []
      playlist.value.posts[0] = []
      playlist.value.posts[0] = reactive({ ...props.post })
      playlist.value.posts[0].disableLink = true
      await fetchPlaylist(props.post.linkedPlaylist.id, 1).then(function (
        response
      ) {
        if (response.posts) {
          response.posts.forEach((item) => {
            playlist.value.posts.push(item)
          })
        }
      })
    }

    return {
      showPlaylist,
      playlist,
      onShowPlaylist,
      loadPlaylist,
    }
  },
})
</script>

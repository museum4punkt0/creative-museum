<template>
  <div v-if="$auth.loggedIn" class="lg:grid lg:grid-cols-12 lg:gap-4">
    <div class="lg:col-span-3 lg:pr-10">
      <SidebarLeft />
    </div>
    <div class="lg:col-span-6 lg:pr-10">
      <div class="flex flex-row content-between">
        <h2 class="text-2xl">
          {{ $t('user.profile.self.activities.headline') }}
        </h2>
      </div>
      <div class="filter flex flex-row flex-wrap mt-6">
        <button
          class="text-sm self-start rounded-full mb-3 mr-3 py-1 px-2"
          :class="mode === 'posts' ? 'btn-primary' : 'btn-outline'"
          @click.prevent="showPosts"
        >
          {{ $t('user.profile.self.activities.posts') }}
        </button>
        <button
          class="px-2 py-1 mr-3 mb-3 rounded-full self-start text-sm"
          :class="mode === 'bookmarks' ? 'btn-primary' : 'btn-outline'"
          @click.prevent="showBookmarks"
        >
          {{ $t('user.profile.self.activities.bookmarks') }}
        </button>
        <button
          class="px-2 py-1 mb-3 rounded-full self-start text-sm"
          :class="mode === 'playlists' ? 'btn-primary' : 'btn-outline'"
          @click.prevent="showPlaylists"
        >
          {{ $t('user.profile.self.activities.playlists') }}
        </button>
      </div>
      <div class="relative pb-10 list">
        <div v-if="mode === 'posts'">
          <PostList v-if="posts && posts.length" :posts="posts" source="userprofile" />
        </div>
        <div v-if="mode === 'bookmarks'">
          <PostItem
            v-for="(post, key) in bookmarks"
            :key="'bookmark_' + key"
            :post="post"
            @updatePost="updatePost"
            @toggle-bookmark-state="removeBookmark"
          />
        </div>
        <div v-if="mode === 'playlists'" class="grid grid-cols-2 gap-6 mt-4">
          <div
            v-for="(playlist, key) in playlists"
            :key="key"
            class="btn-primary"
            @click.prevent="showPlaylist = playlist.id"
          >
            <span class="h-30 flex flex-col align-center justify-center">
              {{ playlist.title }} ({{ playlist.postCount }})
            </span>
          </div>

          <Modal v-if="showPlaylist > 0 && playlistPosts" @closeModal="showPlaylist = 0">
            <div class="flex flex-col flex-1 justify-between">
              <div>
                <div class="page-header p-6">
                  <button type="button" class="back-btn" @click.prevent="showPlaylist = 0">
                    {{ playlistPosts.title }}
                  </button>
                </div>
                <div class="px-4 pb-4">
                  <PostList v-if="playlistPosts" :posts="playlistPosts.posts" source="playlist" class="playlist-items" />
                </div>
              </div>
            </div>
          </Modal>
        </div>
      </div>
    </div>
    <div class="lg:col-span-3 lg:pr-10">
      <SidebarRight />
    </div>
  </div>
</template>

<script>
import {
  defineComponent,
  ref,
  useStore,
  onMounted,
  useContext,
  useRouter,
  watch
} from '@nuxtjs/composition-api'
import { postApi } from '@/api/post'
import { playlistApi } from '@/api/playlist'

export default defineComponent({
    name: "ProfilePage",
    setup() {
        const { fetchUserPosts, fetchUserBookmarks } = postApi()
        const { fetchPlaylist } = playlistApi()
        const mode = ref("posts")
        const store = useStore()
        const router = useRouter()
        const { $config, $auth } = useContext()
        const posts = ref(null)
        const playlists = ref(null)
        const playlistPosts = ref(null)
        const bookmarks = ref(null)

        const showPlaylist = ref(0)

        if (!$auth.loggedIn) {
            router.push('/404')
        }
        store.dispatch('hideAddButton')
        store.dispatch('setCurrentCampaign', null)

        onMounted(async () => {
            posts.value = await fetchUserPosts($auth.user.id, 1)
            playlists.value = $auth.user.playlists
            bookmarks.value = await fetchUserBookmarks()
        })

        watch(showPlaylist, (currentValue) => {
          if (currentValue > 0) {
            loadPlaylist()
          }
        })

        function showPosts() {
            mode.value = 'posts'
        }
        function showBookmarks() {
            mode.value = 'bookmarks'
        }
        function showPlaylists() {
            mode.value = 'playlists'
        }

        async function loadPlaylist() {
          playlistPosts.value = await fetchPlaylist(showPlaylist.value, 1)
        }

        function backButton() { }
        return {
            backButton,
            mode,
            showPlaylist,
            showPosts,
            showBookmarks,
            showPlaylists,
            loadPlaylist,
            posts,
            playlists,
            playlistPosts,
            bookmarks,
            backendUrl: $config.backendUrl
        };
    },
})
</script>

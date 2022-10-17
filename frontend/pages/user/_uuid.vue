<template>
  <div class="lg:grid lg:grid-cols-12 lg:gap-4">
    <div class="lg:col-span-3 lg:pr-10 mb-6 lg:mb-0 lg:order-1">
      <SidebarLeft v-if="user" :user="user" />
    </div>
    <div class="lg:col-span-3 mb-6 lg:mb-0 lg:order-3">
      <SidebarRight v-if="user" :user="user" />
    </div>
    <div class="lg:col-span-6 lg:pr-10 lg:order-2">
      <div class="flex flex-row content-between">
        <h2 class="text-2xl">
          {{ $t('user.profile.activities.headline') }}
        </h2>
      </div>
      <div class="filter flex flex-row flex-wrap mt-6">
        <button
          class="text-sm self-start rounded-full mb-3 mr-3 py-1 px-2"
          :class="mode === 'posts' ? 'btn-primary' : 'btn-outline'"
          @click.prevent="showPosts"
        >
          {{ $t('user.profile.activities.posts') }}
        </button>
        <button
          class="px-2 py-1 mb-3 rounded-full self-start text-sm"
          :class="mode === 'playlists' ? 'btn-primary' : 'btn-outline'"
          @click.prevent="showPlaylists"
        >
          {{ $t('user.profile.activities.playlists') }}
        </button>
      </div>
      <div class="relative pb-10 list">
        <div v-if="mode === 'posts'">
          <PostList
            v-if="posts && posts.length"
            :posts="posts"
            source="userprofile"
          />
        </div>
        <div v-if="mode === 'playlists'" class="grid grid-cols-2 gap-6 mt-4">
          <a
            v-for="(playlist, key) in playlists"
            :key="key"
            class="btn-primary cursor-pointer"
            @click.prevent="showPlaylist = playlist.id"
          >
            <span class="h-30 flex flex-col align-center justify-center">
              {{ playlist.title }}
            </span>
          </a>

          <UtilitiesModal
            v-if="showPlaylist > 0 && playlistPosts"
            @closeModal="showPlaylist = 0"
          >
            <div class="flex flex-col flex-1 justify-between">
              <div>
                <div class="page-header p-6">
                  <button
                    type="button"
                    class="back-btn"
                    @click.prevent="showPlaylist = 0"
                  >
                    {{ playlistPosts.title }}
                  </button>
                </div>
                <div class="px-4 pb-4">
                  <PostList
                    v-if="playlistPosts"
                    :posts="playlistPosts.posts"
                    source="playlist"
                    class="playlist-items"
                  />
                </div>
              </div>
            </div>
          </UtilitiesModal>
        </div>
      </div>
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
  useRoute,
  useRouter,
  watch,
} from '@nuxtjs/composition-api'
import { userApi } from '@/api/user'
import { postApi } from '@/api/post'
import { playlistApi } from '@/api/playlist'

export default defineComponent({
  name: 'ProfilePage',
  setup() {
    const { fetchUser } = userApi()
    const { fetchUserPosts } = postApi()
    const { fetchPlaylist } = playlistApi()
    const user = ref(null)
    const mode = ref('posts')
    const store = useStore()
    const route = useRoute()
    const router = useRouter()
    const { $config, $auth } = useContext()
    const posts = ref(null)
    const playlists = ref(null)
    const playlistPosts = ref(null)

    const showPlaylist = ref(0)

    store.dispatch('hideAddButton')
    store.dispatch('setCurrentCampaign', null)

    onMounted(async () => {
      user.value = await fetchUser(route.value.params.uuid)

      if ((user.value && user.value.error) || user.value && user.deleted) {
        router.push('/404')
      }

      posts.value = await fetchUserPosts(user.value.id, 1)
      playlists.value = user.value.playlists
    })

    watch(showPlaylist, (currentValue) => {
      if (currentValue > 0) {
        loadPlaylist()
      }
    })

    function showPosts() {
      mode.value = 'posts'
    }
    function showPlaylists() {
      mode.value = 'playlists'
    }

    async function loadPlaylist() {
      playlistPosts.value = await fetchPlaylist(showPlaylist.value, 1)
    }

    function backButton() {}
    return {
      user,
      backButton,
      mode,
      showPlaylist,
      showPosts,
      showPlaylists,
      loadPlaylist,
      posts,
      playlists,
      playlistPosts,
      backendUrl: $config.backendUrl,
    }
  },
})
</script>

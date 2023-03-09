<template>
  <div v-if="$auth.loggedIn" class="lg:grid lg:grid-cols-12 lg:gap-4 ">
    <div class="lg:col-span-3 lg:pr-10 lg:order-1 mb-6 lg:mb-0">
      <SidebarLeft />
    </div>
    <div class="lg:col-span-3 lg:order-3 mb-6 lg:mb-0">
      <SidebarRight />
    </div>
    <div id="top" class="lg:col-span-6 lg:pr-10 lg:order-2">
      <div class="flex flex-row content-between">
        <h2 class="text-2xl md:hidden">
          {{ $t('user.profile.self.activities.headline') }}
        </h2>
        <UtilitiesBackButton class="hidden md:block">
          {{ $t('user.profile.self.activities.headline') }}
        </UtilitiesBackButton>
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
          class="px-2 py-1 mr-3 mb-3 rounded-full self-start text-sm"
          :class="mode === 'bookmarks' ? 'btn-primary' : 'btn-outline'"
          @click.prevent="showBookmarks"
        >
          {{ $t('user.profile.activities.bookmarks') }}
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
        <div v-if="mode === 'bookmarks'">
          <PostList
            v-if="bookmarks && bookmarks.length"
            :posts="bookmarks"
            source="userprofile"
            @updatePost="refetchBookmarks"
          />
        </div>
        <div v-if="mode === 'playlists'" class="grid grid-cols-2 gap-6 mt-4">
          <a
            v-for="(playlist, key) in playlists"
            :key="key"
            class="relative btn-primary cursor-pointer"
            @click.prevent="showPlaylist = playlist.id"
          >
            <span class="h-30 flex flex-col align-center justify-center">
              {{ playlist.title }}
            </span>
            <span role="button w-5 h-5 inline-block" class="absolute top-1 right-1 p-2" @click.stop="onDeletePlaylist(playlist.id)">
              <TrashIcon class="h-3 w-3 fill-$highlight-contrast" />
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
    <div v-if="!isLargerThanLg" class="xl:hidden">
      <PageFooter />
    </div>
    <UtilitiesModal
        v-if="showDeleteModal"
        class="flex flex-col h-full"
        :closable="true"
        tabindex="0"
        @closeModal="showDeleteModal = false"
      >
      <div
        class="flex flex-col flex-1 h-full justify-between"
      >
        <div>
          <div class="page-header px-6">
            <a class="back-btn" @click="additionalPage = false">{{ $t('post.actions.delete.headline') }}</a>
          </div>
          <div class="box-shadow-mobile relative m-6 lg:m-0 p-6">
            {{ $t('post.actions.delete.confirmation') }}
          </div>
        </div>
        <div class="mx-6 mb-6">
          <button
            class="btn-primary bg-$highlight text-$highlight-contrast border-$highlight w-full mb-4"
            @click.prevent="confirmDeletePlaylist"
          >
            {{ $t('post.actions.delete.button') }}
          </button>
          <button
            class="btn-outline w-full"
            @click.prevent="showDeleteModal = false"
          >
            {{ $t('close') }}
          </button>
        </div>
      </div>
    </UtilitiesModal>
  </div>
</template>

<script>
import {
  computed,
  defineComponent,
  ref,
  useStore,
  onMounted,
  useContext,
  useRouter,
  watch,
  useMeta,
} from '@nuxtjs/composition-api'
import { postApi } from '@/api/post'
import { playlistApi } from '@/api/playlist'
import TrashIcon from '@/assets/icons/trash.svg?inline'

export default defineComponent({
  name: 'ProfilePage',
  components: {
    TrashIcon
  },
  layout: 'BreakScrollDefault',
  setup() {
    const { fetchUserPosts, fetchUserBookmarks } = postApi()
    const { fetchPlaylist, deletePlaylist } = playlistApi()
    const mode = ref('posts')
    const store = useStore()
    const router = useRouter()
    const { $config, $auth, $breakpoints, i18n, $confirm } = useContext()
    const posts = ref(null)
    const playlistPosts = ref(null)
    const bookmarks = ref(null)
    const showPlaylist = ref(0)
    const showDeleteModal = ref(false)
    const playlistToDelete = ref(0)

    const isLargerThanLg = computed(() => {
      return $breakpoints.lLg
    })

    const playlists = computed(() => {
      return $auth.user.playlists
    })

    useMeta({
      title: i18n.t('pages.profile.title') + ' | ' + i18n.t('pageTitle')
    })

    if (!$auth.loggedIn) {
      router.push('/404')
    }
    store.dispatch('hideAddButton')
    store.dispatch('setCurrentCampaign', null)

    onMounted(async () => {
      posts.value = await fetchUserPosts($auth.user.id, 1)
      bookmarks.value = await fetchUserBookmarks()
    })


    watch(showPlaylist, (currentValue) => {
      if (currentValue > 0) {
        loadPlaylist()
      }
    })

    async function refetchBookmarks() {
      bookmarks.value = await fetchUserBookmarks()
    }

    function showPosts() {
      mode.value = 'posts'
    }
    function showBookmarks() {
      mode.value = 'bookmarks'
    }
    function showPlaylists() {
      playlists.value = $auth.user.playlists
      mode.value = 'playlists'
    }

    async function loadPlaylist() {
      playlistPosts.value = await fetchPlaylist(showPlaylist.value, 1)
    }

    function onDeletePlaylist(playlistId) {
      showDeleteModal.value = true
      playlistToDelete.value = playlistId
    }

    async function confirmDeletePlaylist() {
      await deletePlaylist(playlistToDelete.value).then(() => {
        playlistToDelete.value = 0
        showDeleteModal.value = false
      })
    }

    return {
      mode,
      showPlaylist,
      showDeleteModal,
      showPosts,
      showBookmarks,
      showPlaylists,
      loadPlaylist,
      refetchBookmarks,
      onDeletePlaylist,
      confirmDeletePlaylist,
      posts,
      playlists,
      playlistPosts,
      bookmarks,
      isLargerThanLg,
      backendUrl: $config.backendUrl,
    }
  },
  head: {}
})
</script>

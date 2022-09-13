<template>
  <div v-if="post.author" class="flex flex-row justify-between">
    <NuxtLink :to="$auth.loggedIn && post.author.uuid === $auth.user.uuid ? localePath('/user/profile') : localePath(`/user/${post.author.uuid}`)" class="flex flex-row">
      <UserProfileImage :user="post.author" class="mr-4" />
      <div class="flex flex-col">
        <span class="text-lg">{{ post.author.username }}</span>
        <span
          :class="post.type !== 'playlist' ? 'highlight-text' : ''"
          class="text-sm mt-1"
          >{{
            $dayjs.duration($dayjs().diff($dayjs(post.created))).days() > 2
              ? $dayjs(post.created).format('DD.MM.YYYY')
              : $dayjs(post.created).fromNow()
          }}</span
        >
      </div>
    </NuxtLink>
    <div @click.prevent="onShowAdditionalOptions">
      <UtilitiesThreeDots
        class="cursor-pointer"
        :text-color="post.type === 'playlist' ? textColor : 'white'"
      />
    </div>
    <transition
      enter-active-class="duration-300 ease-out -bottom-full lg:opacity-0 lg:bottom-auto"
      enter-to-class="bottom-0 lg:bottom-auto lg:opacity-100"
      leave-active-class="duration-200 ease-in"
      leave-class="bottom-0 lg:bottom-auto lg:top-1/2 lg:opacity-100"
      leave-to-class="bottom-full lg:bottom-auto lg:opacity-0"
    >
      <component
        :is="`Utilities${modalType}`"
        v-if="showAdditionalOptions"
        class="flex flex-col h-full"
        :closable="modalType === 'SlideUp' ? true : false"
        @closeModal="showAdditionalOptions = false"
      >
        <div v-if="!additionalPage" class="flex flex-col p-6 mr-12">
          <h3 class="mb-6">{{ $t('post.moreActions') }}</h3>
          <ul class="text-base">
            <li class="my-6">
              <button
                v-if="!post.bookmarked"
                @click="addOrRemoveBookmark(post.id)"
              >
                {{ $t('post.actions.addBookmark') }}
              </button>
            </li>
            <li class="my-6">
              <button
                v-if="post.bookmarked"
                @click="addOrRemoveBookmark(post.id)"
              >
                {{ $t('post.actions.removeBookmark') }}
              </button>
            </li>
            <li class="my-6">
              <button
                class="block btn-right"
                @click="openPlaylistSelectionModal"
              >
                {{ $t('post.actions.addToPlaylist') }}
              </button>
            </li>
            <li v-if="( $auth.loggedIn && $auth.user ) && ($auth.user.uuid === post.author.uuid)" class="my-6">
              <button
                class="block"
                @click="showDeleteDialog"
              >
                {{ $t('post.actions.delete.button') }}
              </button>
            </li>
          </ul>
        </div>
        <div v-if="additionalPage" class="flex flex-col flex-1 items-stretch">
          <div
            v-if="additionalPageContent === 'playlistSelection'"
            class="flex flex-col flex-1 items-stretch"
          >
            <PlaylistSelection
              class="flex flex-col flex-1 items-stretch"
              @closeModal="additionalPage = false"
              @createPlaylist="addPostToNewPlaylist"
              @selectPlaylist="addPostToPlaylist"
            />
          </div>
          <div
            v-if="additionalPageContent === 'deleteDialog'"
            class="flex flex-col flex-1 h-full justify-between"
          >
            <div>
              <p class="m-6 text-lg">{{ $t('post.actions.delete.confirmation') }}</p>
            </div>
            <div class="mx-6 mb-6">
              <button
                class="btn-primary bg-$highlight text-$highlight-contrast border-$highlight w-full mb-4"
                @click.prevent="deletePost"
              >
              {{ $t('post.actions.delete.button')}}
              </button>
              <button class="btn-outline w-full" @click.prevent="additionalPageContent = ''; additionalPage = false">{{ $t('close') }}</button>
            </div>
          </div>
        </div>
      </component>
    </transition>
  </div>
</template>
<script>
import {
  defineComponent,
  ref,
  computed,
  useContext,
  useStore
} from '@nuxtjs/composition-api'
import { postApi } from '@/api/post'

export default defineComponent({
  props: {
    post: {
      type: Object,
      required: true,
    },
    textColor: {
      type: String,
      required: true,
    },
  },
  emits: [
    'toggleBookmarkState',
    'postDeleted'
  ],
  setup(props, context) {
    const { toggleBookmark, addToPlaylist, createPlaylistWithPost, deletePostById } = postApi()

    const store = useStore()

    const showAdditionalOptions = ref(false)
    const additionalPage = ref(false)
    const additionalPageContent = ref('')

    const modalType = computed(() => {
      return additionalPage.value ? 'Modal' : 'SlideUp'
    })

    const { $auth } = useContext()

    async function addOrRemoveBookmark(postId) {
      await toggleBookmark(postId)
      context.emit('toggleBookmarkState', postId)
    }

    function openPlaylistSelectionModal() {
      additionalPageContent.value = 'playlistSelection'
      additionalPage.value = true
    }

    function addPostToPlaylist(playlistId) {
      addToPlaylist(playlistId, props.post.id)
      additionalPage.value = false
      showAdditionalOptions.value = false
    }

    function addPostToNewPlaylist(playlistName) {
      createPlaylistWithPost(props.post.id, playlistName).then(function () {
        $auth.fetchUser()
        additionalPage.value = false
        showAdditionalOptions.value = false
      })
    }

    function onShowAdditionalOptions() {
      if (!$auth.loggedIn) {
        store.dispatch('showLogin')
      } else {
        showAdditionalOptions.value = true
      }
    }

    function showDeleteDialog() {
      additionalPageContent.value = 'deleteDialog'
      additionalPage.value = true
    }

    async function deletePost() {
      await deletePostById(props.post.id)
      context.emit('postDeleted')
      additionalPageContent.value = ''
      additionalPage.value = false
    }

    return {
      showAdditionalOptions,
      additionalPage,
      additionalPageContent,
      modalType,
      showDeleteDialog,
      addOrRemoveBookmark,
      openPlaylistSelectionModal,
      addPostToPlaylist,
      addPostToNewPlaylist,
      onShowAdditionalOptions,
      deletePost
    }
  },
})
</script>

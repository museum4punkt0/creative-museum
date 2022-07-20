<template>
  <div
    v-if="post.author"
    w:flex="~ row"
    w:justify="between"
  >
    <NuxtLink
      :to="localePath(`/user/${post.author.username}`)"
      w:flex="~ row"
    >
      <UserProfileImage :user="post.author" w:m="r-4" />
      <div w:flex="~ col">
        <span w:text="lg">{{ post.author.username }}</span>
        <span class="highlight-text" w:text="sm" w:m="t-1">{{ $dayjs.duration($dayjs().diff($dayjs(post.created))).days() > 2 ? $dayjs(post.created).format('DD.MM.YYYY') : $dayjs(post.created).fromNow() }}</span>
      </div>
    </NuxtLink>
    <div @click="showAdditionalOptions = !showAdditionalOptions">
      <ThreeDots w:cursor="pointer"  />
    </div>
    <transition
      enter-active-class="duration-300 ease-out -bottom-full lg:opacity-0 lg:bottom-auto"
      enter-to-class="bottom-0 lg:bottom-auto lg:opacity-100"
      leave-active-class="duration-200 ease-in"
      leave-class="bottom-0 lg:bottom-auto lg:top-1/2 lg:opacity-100"
      leave-to-class="bottom-full lg:bottom-auto lg:opacity-0"
    >
      <SlideUp v-if="showAdditionalOptions" :closable="true" @closeSlideUp="showAdditionalOptions = false">
        <div w:p="6">
          {{ $t('post.moreActions') }}
          <ul>

            <li @click="addOrRemoveBookmark(post.id)" v-if="! post.bookmarked">
              {{ $t('post.actions.addBookmark') }}
            </li>
            <li @click="addOrRemoveBookmark(post.id)" v-if="post.bookmarked">
              {{ $t('post.actions.removeBookmark') }}
            </li>

            <li @click="openPlaylistSelectionModal">
              {{ $t('post.actions.addToPlaylist') }}
            </li>

            <!--
            <li>Teilen</li>
            <li>Kopieren</li>
            <li>Melden</li>
            -->
          </ul>
        </div>
      </SlideUp>
    </transition>

    <Modal v-if="modalOpen">
      <div v-if="modalContent === 'playlistSelection'">
        <PlaylistSelection
          @closeModal="modalOpen = false"
          @createPlaylist="addPostToNewPlaylist"
          @selectPlaylist="addPostToPlaylist" />
      </div>
    </Modal>
  </div>
</template>
<script>
import { defineComponent, ref } from '@nuxtjs/composition-api'
import { postApi } from '@/api/post'

export default defineComponent({
    props: {
      post: {
        type: Object,
        required: true
      }
    },
    setup(props, context) {
      const { toggleBookmark, addToPlaylist, createPlaylistWithPost } = postApi()

      const showAdditionalOptions = ref(false)
      const modalOpen = ref(false)
      const modalContent = ref('')

      function addOrRemoveBookmark(postId) {
        toggleBookmark(postId)
        context.emit('toggle-bookmark-state', postId)
      }

      function openPlaylistSelectionModal() {
        modalContent.value = 'playlistSelection'
        modalOpen.value = true
      }

      function addPostToPlaylist(playlistId) {
        addToPlaylist(playlistId, props.post.id)
        modalOpen.value = false
        showAdditionalOptions.value = false
      }

      function addPostToNewPlaylist(playlistName) {
        createPlaylistWithPost(props.post.id, playlistName)
        modalOpen.value = false
        showAdditionalOptions.value = false
      }

      return {
        showAdditionalOptions,
        addOrRemoveBookmark,
        modalOpen,
        modalContent,
        openPlaylistSelectionModal,
        addPostToPlaylist,
        addPostToNewPlaylist
      }
    }
})
</script>

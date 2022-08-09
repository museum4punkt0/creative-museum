<template>
  <div v-if="post.author" w:flex="~ row" w:justify="between">
    <NuxtLink :to="localePath(`/user/${post.author.username}`)" w:flex="~ row">
      <UserProfileImage :user="post.author" w:m="r-4" />
      <div w:flex="~ col">
        <span w:text="lg">{{ post.author.username }}</span>
        <span :class="post.type !== 'playlist' ? 'highlight-text' : ''" w:text="sm" w:m="t-1">{{
          $dayjs.duration($dayjs().diff($dayjs(post.created))).days() > 2
            ? $dayjs(post.created).format('DD.MM.YYYY')
            : $dayjs(post.created).fromNow()
        }}</span>
      </div>
    </NuxtLink>
    <div @click="showAdditionalOptions = !showAdditionalOptions">
      <ThreeDots w:cursor="pointer" />
    </div>
    <transition
      enter-active-class="duration-300 ease-out -bottom-full lg:opacity-0 lg:bottom-auto"
      enter-to-class="bottom-0 lg:bottom-auto lg:opacity-100"
      leave-active-class="duration-200 ease-in"
      leave-class="bottom-0 lg:bottom-auto lg:top-1/2 lg:opacity-100"
      leave-to-class="bottom-full lg:bottom-auto lg:opacity-0"
    >
      <component
        :is="modalType"
        v-if="showAdditionalOptions"
        w:h="full"
        w:flex="~ col"
        :closable="modalType === 'SlideUp' ? true : false"
        @closeModal="showAdditionalOptions = false"
      >
        <div v-if="!additionalPage" w:flex="~ col" w:p="6" w:mr="12">
          <h3 w:mb="6">{{ $t('post.moreActions') }}</h3>
          <ul w:text="base">
            <li w:my="6">
              <button
                v-if="!post.bookmarked"
                @click="addOrRemoveBookmark(post.id)"
              >
                {{ $t('post.actions.addBookmark') }}
              </button>
            </li>
            <li w:my="6">
              <button
                v-if="post.bookmarked"
                @click="addOrRemoveBookmark(post.id)"
              >
                {{ $t('post.actions.removeBookmark') }}
              </button>
            </li>
            <li w:my="6">
              <button class="block btn-right" @click="openPlaylistSelectionModal">
                {{ $t('post.actions.addToPlaylist') }}
              </button>
            </li>
            <!--
            <li>Teilen</li>
            <li>Kopieren</li>
            <li>Melden</li>
            -->
          </ul>
        </div>
        <div w:flex="~ col 1" w:align="items-stretch" v-if="additionalPage">
          <div w:flex="~ col 1" w:align="items-stretch" v-if="additionalPageContent === 'playlistSelection'">
            <PlaylistSelection
              w:flex="~ col 1" w:align="items-stretch"
              @closeModal="additionalPage = false"
              @createPlaylist="addPostToNewPlaylist"
              @selectPlaylist="addPostToPlaylist"
            />
          </div>
        </div>
      </component>
    </transition>
  </div>
</template>
<script>
import { defineComponent, ref, computed, useContext } from '@nuxtjs/composition-api'
import { postApi } from '@/api/post'

export default defineComponent({
  props: {
    post: {
      type: Object,
      required: true,
    },
  },
  setup(props, context) {
    const { toggleBookmark, addToPlaylist, createPlaylistWithPost } = postApi()

    const showAdditionalOptions = ref(false)
    const additionalPage = ref(false)
    const additionalPageContent = ref('')

    const modalType = computed(() => {
      return additionalPage.value ? 'Modal' : 'SlideUp'
    })

    const { $auth } = useContext()

    async function addOrRemoveBookmark(postId) {
      await toggleBookmark(postId)
      context.emit('toggle-bookmark-state', postId)
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
      createPlaylistWithPost(props.post.id, playlistName).then(function() {
        $auth.fetchUser()
        additionalPage.value = false
        showAdditionalOptions.value = false
      })
    }

    return {
      showAdditionalOptions,
      additionalPage,
      additionalPageContent,
      modalType,
      addOrRemoveBookmark,
      openPlaylistSelectionModal,
      addPostToPlaylist,
      addPostToNewPlaylist,
    }
  },
})
</script>

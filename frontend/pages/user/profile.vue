<template>
  <div>
    <div class="grid grid-cols-1 lg:grid-cols-12 lg:gap-4">
      <div class="grid col-span-3 pr-10">
        <div class="page-header p-6 md:hidden">
          <button class="back-btn" @click.prevent="backButton">
            {{
              $t('user.profile.self.headline', { firstName: user.firstName })
            }}
          </button>
        </div>

        <img
          v-if="'profilePicture' in user"
          :src="
            'https://backend.creative-museum.ddev.site' +
            user.profilePicture.contentUrl
          "
          class="rounded-full mb-4 h-21 w-21"
        />

        <h1 class="text-2xl">{{ user.firstName }} {{ user.lastName }}</h1>
        <p class="highlight-text mb-2">@{{ user.username }}</p>
        <p>
          {{ user.description }}
        </p>

        <NuxtLink
          to="/user/update"
          class="btn-primary btn-outline md:self-start mt-10 mb-12"
        >
          {{ $t('user.editProfile') }}</NuxtLink
        >

        <h2 class="text-2xl">{{ $t('user.profile.self.points') }}</h2>

        <div
          v-for="membership in user.memberships"
          class="self-stretch md:self-start mt-3 mb-12"
        >
          <div class="highlight-text text-sm mb-2">
            {{ membership.campaign.title }}
          </div>
          <div
            class="box-shadow justify-center items-end flex flex-row rounded-full py-2 px-4"
          >
            <span class="text-2xl mr-2">{{ membership.score }}</span>
            <span>{{ $t('user.profile.self.score') }}</span>
          </div>
        </div>
      </div>
      <div class="grid col-span-6 pr-10">
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
        <div class="list">
          <div v-if="mode === 'posts'">
            <PostItem
              v-for="(post, key) in posts"
              :key="'post_' + key"
              :post="post"
              @updatePost="updatePost"
              @toggle-bookmark-state="toggleBookmarkState"
            />
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
          <div v-if="mode === 'playlists'">
            <div v-for="(item, key) in playlists">
              {{ item.title }} ({{ item.postCount }})
            </div>
          </div>
        </div>
      </div>
      <div class="grid col-span-3 pr-10">
        <div class="mb-12">
          <h2 class="text-2xl">{{ $t('user.profile.self.notifications') }}</h2>
        </div>
        <div class="mb-12">
          <h2 class="text-2xl">{{ $t('user.profile.self.awards') }}</h2>
        </div>
        <div class="mb-12">
          <div class="flex flex-row content-between">
            <h2 class="text-2xl">{{ $t('user.profile.self.badges.headline') }}</h2>

            <button
              class="highlight-text text-sm flex flex-row items-center leading-none cursor-pointer"
              @click.prevent="toggleShowMore"
            >
              <ArrowIcon
                class="relative w-2 top-0 mr-0.5 inline-block transition-all duration-200"
                :class="readMore ? 'transform-gpu rotate-180' : ''"
              />
              <span v-if="!readMore">{{ $t('showAll') }}</span>
              <span v-else>{{ $t('hide') }}</span>
            </button>
          </div>

          <div
            v-for="(achievement, index) in user.achievements"
            v-if="index < 2 || readMore"
            class="flex flex-row mb-6"
          >
            <img
              :src="
                'https://backend.creative-museum.ddev.site' +
                achievement.badge.picture.contentUrl
              "
              class="self-center w-20"
            />
            <div class="content-center flex flex-col ml-4">
              <span>{{ achievement.badge.title }}</span>
              <span class="highlight-text text-sm">{{
                $t(
                  'user.profile.self.badges.points.' + achievement.badge.type,
                  { threshold: achievement.badge.threshold }
                )
              }}</span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import {
  defineComponent,
  computed,
  ref,
  useStore,
  onMounted,
} from '@nuxtjs/composition-api'
import { notificationApi } from '@/api/notification'
import ArrowIcon from '@/assets/icons/arrow.svg?inline'
import { postApi } from '@/api/post'

export default defineComponent({
  name: 'ProfilePage',
  components: {
    ArrowIcon,
  },
  setup() {
    const { getUserPosts, fetchPost, getUserBookmarks } = postApi()
    const { getNotifications } = notificationApi()

    const readMore = ref(false)
    const mode = ref('posts')
    const store = useStore()
    const user = computed(() => store.state.auth.user)
    const notifications = getNotifications()
    const posts = ref(null)
    const playlists = ref(null)
    const bookmarks = ref(null)

    onMounted(async () => {
      posts.value = await getUserPosts()
      playlists.value = store.$auth.state.user.playlists
      bookmarks.value = await getUserBookmarks()
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

    function backButton() {}

    function toggleShowMore() {
      readMore.value = !readMore.value
    }

    function updatePost(postId) {
      fetchPost(postId).then(function (response) {
        posts.value.forEach(function (item, key) {
          if (item.id === postId) {
            posts.value[key].commentCount = response.commentCount
          }
        })
        bookmarks.value.forEach(function (item, key) {
          if (item.id === postId) {
            bookmarks.value[key].commentCount = response.commentCount
          }
        })
      })
    }

    async function toggleBookmarkState(postId) {
      posts.value.forEach((item, key) => {
        if (item.id !== postId) {
          return
        }
        posts.value[key].bookmarked = !posts.value[key].bookmarked
      })
      bookmarks.value = await getUserBookmarks()
    }

    async function removeBookmark(postId) {
      bookmarks.value.forEach((item, key) => {
        if (item.id !== postId) {
          return
        }
        bookmarks.value[key].bookmarked = !bookmarks.value[key].bookmarked
      })
      bookmarks.value = await getUserBookmarks()
    }

    return {
      user,
      backButton,
      notifications,
      toggleShowMore,
      readMore,
      mode,
      showPosts,
      showBookmarks,
      showPlaylists,
      posts,
      playlists,
      bookmarks,
      updatePost,
      toggleBookmarkState,
      removeBookmark,
    }
  },
})
</script>

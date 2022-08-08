<template>
  <div>
    <div w:grid="~ cols-1 lg:cols-12 lg:gap-4">
      <div w:grid="col-span-3" w:pr="10">
        <div w:p="6" w:display="md:hidden" class="page-header">
          <button class="back-btn" @click.prevent="backButton">
            {{ $t('user.profile.self.headline', {firstName: user.firstName}) }}
          </button>
        </div>

        <img
          v-if="'profilePicture' in user"
          :src="'https://backend.creative-museum.ddev.site' + user.profilePicture.contentUrl"
          w:w="21"
          w:h="21"
          w:mb="4"
          w:rounded="full"
        />

        <h1 w:text="2xl">{{ user.firstName }} {{ user.lastName }}</h1>
        <p class="highlight-text" w:mb="2">@{{ user.username }}</p>
        <p>
          {{ user.description }}
        </p>

        <NuxtLink to="/user/update" w:align="md:self-start" w:mt="10" w:mb="12" class="btn-primary btn-outline"> {{ $t('user.editProfile') }}</NuxtLink>

        <h2 w:text="2xl">{{ $t('user.profile.self.points') }}</h2>

        <div v-for="membership in user.memberships" w:align="self-stretch md:self-start" w:mt="3" w:mb="12">
          <div w:text="sm" w:mb="2" class="highlight-text">{{ membership.campaign.title }}</div>
          <div
            w:px="4"
            w:py="2"
            w:rounded="full"
            w:flex="~ row"
            w:align="items-end"
            w:justify="center"
            class="box-shadow"
          >
            <span w:text="2xl" w:mr="2">{{ membership.score }}</span>
            <span>{{ $t('user.profile.self.score') }}</span>
          </div>
        </div>
      </div>
      <div w:grid="col-span-6" w:pr="10">

        <div w:flex="~row" w:justify="content-between">
          <h2 w:text="2xl">{{ $t('user.profile.self.activities.headline') }}</h2>
        </div>
        <div class="filter">
          <button @click.prevent="showPosts">{{ $t('user.profile.self.activities.posts') }}</button>
          <button @click.prevent="showBookmarks">{{ $t('user.profile.self.activities.bookmarks') }}</button>
          <button @click.prevent="showPlaylists">{{ $t('user.profile.self.activities.playlists') }}</button>
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
              @toggle-bookmark-state="toggleBookmarkState"
            />
          </div>
          <div v-if="mode === 'playlists'">
            <div v-for="(item, key) in playlists">
              {{ item.title }} ({{ item.postCount }})
            </div>
          </div>
        </div>

      </div>
      <div w:grid="col-span-3" w:pr="10">
        <div w:mb="12">
          <h2 w:text="2xl">{{ $t('user.profile.self.notifications') }}</h2>

        </div>
        <div w:mb="12">
          <h2 w:text="2xl">{{ $t('user.profile.self.awards') }}</h2>

        </div>
        <div w:mb="12">
          <div w:flex="~ row" w:justify="content-between">
            <h2 w:text="2xl">{{ $t('user.profile.self.badges.headline') }}</h2>

            <button class="highlight-text" w:text="sm" w:flex="~ row" w:align="items-center" w:font="leading-none" w:cursor="pointer" @click.prevent="toggleShowMore">
              <ArrowIcon w:pos="relative" w:w="2" w:top="0" w:m="r-0.5" w:display="inline-block" w:transition="all duration-200" :w:transform="readMore ? 'gpu rotate-180' : ''" />
              <span v-if="! readMore">{{ $t('showAll') }}</span>
              <span v-else>{{ $t('hide') }}</span>
            </button>
          </div>

          <div v-if="index < 2 || readMore" v-for="(achievement, index) in user.achievements" w:flex="~ row" w:mb="6">
            <img :src="'https://backend.creative-museum.ddev.site' + achievement.badge.picture.contentUrl" w:w="20" w:align="self-center" />
            <div w:ml="4" w:flex="~ col" w:justify="content-center">
              <span>{{ achievement.badge.title }}</span>
              <span w:text="sm" class="highlight-text">{{ $t('user.profile.self.badges.points.' + achievement.badge.type, { threshold: achievement.badge.threshold })}}</span>
            </div>
          </div>
        </div>

      </div>


    </div>
  </div>
</template>


<script>

import {defineComponent, computed, ref, useStore, onMounted} from '@nuxtjs/composition-api'
import { notificationApi } from '@/api/notification'
import ArrowIcon from '@/assets/icons/arrow.svg?inline'
import { postApi } from '@/api/post'

export default defineComponent({
  name: 'ProfilePage',
  components: {
    ArrowIcon
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
      posts.value = await getUserPosts();
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

    function backButton() {

    }

    function toggleShowMore() {
      readMore.value = !readMore.value;
    }

    function updatePost(postId) {
      posts.value.forEach(function(item, key) {
        if (item.id === postId) {
          fetchPost(postId).then(function(response) {
            posts.value[key].commentCount = response.commentCount
          })
        }
      })
    }

    function toggleBookmarkState(postId) {
      posts.value.forEach((item, key) => {
        if (item.id !== postId) {
          return
        }
        posts.value[key].bookmarked = !posts.value[key].bookmarked
      })
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
      toggleBookmarkState
    }
  }
})
</script>

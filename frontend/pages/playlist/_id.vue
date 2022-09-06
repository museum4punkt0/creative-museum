<template>
  <div v-if="$auth.loggedIn" class="lg:grid lg:grid-cols-12 lg:gap-4">
    <div class="lg:col-span-3 lg:pr-10">
      <div class="page-header p-6 md:hidden">
        <button type="button" class="back-btn" @click.prevent="backButton">
          {{
            $t('user.profile.self.headline', { firstName: $auth.user.firstName })
          }}
        </button>
      </div>

      <img
        v-if="'profilePicture' in $auth.user"
        :src="`${backendUrl}/${$auth.user.profilePicture.contentUrl}`"
        class="rounded-full mb-4 h-21 w-21"
      />

      <h1 class="text-2xl">{{ $auth.user.firstName }} {{ $auth.user.lastName }}</h1>
      <p class="highlight-text mb-2">@{{ $auth.user.username }}</p>
      <p>{{ $auth.user.description }}</p>

      <NuxtLink
        to="/user/update"
        class="btn-primary btn-outline md:self-start mt-10 mb-12"
      >
        {{ $t('user.editProfile') }}</NuxtLink
      >

      <h2 class="text-2xl">{{ $t('points') }}</h2>

      <div
        v-for="(membership, key) in $auth.user.memberships"
        :key="key"
        class="self-stretch md:self-start mt-3 mb-12"
        :style="`--highlight: ${membership.campaign.color};`"
      >
        <div class="text-$highlight text-sm mb-2">
          {{ membership.campaign.title }}
        </div>
        <div class="box-shadow justify-center items-end flex flex-row rounded-full py-2 px-4">
          <span class="text-2xl mr-2">{{ membership.score.toLocaleString() }}</span>
          <span>{{ $t('points') }}</span>
        </div>
      </div>
    </div>
    <div class="lg:col-span-6 lg:pr-10">
      <div class="flex flex-row content-between">
        <h2 class="text-2xl">
          {{ $t('playlist.title') }}
        </h2>
      </div>
      <div class="list">
        <PostList v-if="posts && posts.length" :posts="posts" source="playlist" />
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
    useRoute,
    useContext
  } from '@nuxtjs/composition-api'
  import { playlistApi } from '@/api/playlist'

export default defineComponent({
  setup() {
    const store = useStore()
    const route = useRoute()
    const { $config } = useContext()
    const { fetchPlaylist } = playlistApi()

    const posts = ref(null)

    store.dispatch('hideAddButton')
    store.dispatch('setCurrentCampaign', null)

    onMounted(async () => {
      posts.value = await fetchPlaylist(route.value.params.id, 1)
    })

    return {
      posts,
      backendUrl: $config.backendUrl
    }
  },
})
</script>

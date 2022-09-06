<template>
  <div v-if="playlist" class="lg:grid lg:grid-cols-12 lg:gap-4">
    <div class="lg:col-span-3 lg:pr-10">
      <SidebarLeft />
    </div>
    <div class="lg:col-span-6 lg:pr-10">
      <div class="flex flex-row content-between">
        <h2 class="text-2xl">
          {{  playlist.title }}
        </h2>
      </div>
      <div class="list">
        <PostList v-if="playlist && playlist.posts && playlist.posts.length" :posts="playlist.posts" source="playlist" />
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
    useRouter,
    useContext
  } from '@nuxtjs/composition-api'
  import { playlistApi } from '@/api/playlist'

export default defineComponent({
  setup() {
    const store = useStore()
    const route = useRoute()
    const router = useRouter()
    const { $config } = useContext()
    const { fetchPlaylist } = playlistApi()

    const playlist = ref(null)

    store.dispatch('hideAddButton')
    store.dispatch('setCurrentCampaign', null)

    onMounted(async () => {
      playlist.value = await fetchPlaylist(route.value.params.id, 1)

      if (playlist.value && playlist.value.error) {
        router.push('/404')
      }
    })

    return {
      playlist,
      backendUrl: $config.backendUrl
    }
  },
})
</script>

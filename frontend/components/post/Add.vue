<template>
  <div class="container p-6">
    <h2 class="text-xl mb-10">{{ $t('post.new') }}</h2>
    <div class="grid grid-cols-2 md:grid-cols-6 gap-4 lh:max-w-3xl">
      <button
        v-for="(item, key) in addMenuItems"
        :key="key"
        class="box-shadow flex flex-col justify-center items-center"
        @click.prevent="openAddModal(item)"
        type="button"
      >
        <component :is="`Post${item}Icon`" class="w-8 h-8 mb-2" />
        <span class="block text-center">{{
          $t(`post.types.${item.toLowerCase()}.button`)
        }}</span>
      </button>
    </div>
  </div>
</template>
<script>
import { defineComponent, ref, useContext, useStore } from '@nuxtjs/composition-api'
import PostTextIcon from '@/assets/icons/postText.svg?inline'
import PostImageIcon from '@/assets/icons/postImage.svg?inline'
import PostPollIcon from '@/assets/icons/postPoll.svg?inline'
import PostAudioIcon from '@/assets/icons/postAudio.svg?inline'
import PostVideoIcon from '@/assets/icons/postVideo.svg?inline'
import PostPlaylistIcon from '@/assets/icons/postPlaylist.svg?inline'

export default defineComponent({
  components: {
    PostTextIcon,
    PostImageIcon,
    PostPollIcon,
    PostAudioIcon,
    PostVideoIcon,
    PostPlaylistIcon,
  },
  emits: ['openAddModal'],
  setup(_, context) {

    const { $auth } = useContext()
    const store = useStore()

    const openModal = ref('')

    const addMenuItems = ['Text', 'Image', 'Poll', 'Audio', 'Video', 'Playlist']

    function openAddModal($type) {
      if (!$auth.loggedIn) {
        store.dispatch('showLogin')
      } else {
        context.emit('openAddModal', $type)
      }
    }

    return {
      addMenuItems,
      openModal,
      openAddModal,
    }
  },
})
</script>

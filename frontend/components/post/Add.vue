<template>
  <div w:container="~" w:p="6">
    <h2 w:text="xl" w:m="b-10">{{ $t('post.new') }}</h2>
    <div w:grid="~ cols-2 md:cols-6 gap-4" w:max-w="lg:3xl">
      <button
        v-for="(item, key) in addMenuItems"
        :key="key"
        class="box-shadow"
        w:flex="~ col"
        w:justify="center"
        w:align="items-center"
        @click.prevent="openAddModal(item)"
      >
        <component :is="`Post${item}Icon`" w:w="8" w:h="8" w:m="b-2" />
        <span w:display="block" w:text="center">{{
          $t(`post.types.${item.toLowerCase()}.button`)
        }}</span>
      </button>
    </div>
  </div>
</template>
<script>
import { defineComponent, ref } from '@nuxtjs/composition-api'
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
    const openModal = ref('')

    const addMenuItems = ['Text', 'Image', 'Poll', 'Audio', 'Video', 'Playlist']

    function openAddModal($type) {
      context.emit('openAddModal', $type)
    }

    return {
      addMenuItems,
      openModal,
      openAddModal,
    }
  },
})
</script>

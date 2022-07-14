<template>
  <div w:p="6">
    <h2 w:text="xl" w:m="b-10">{{ $t('post.new') }}</h2>
    <div w:grid="~ cols-2 md:cols-6 gap-4" w:max-w="lg:3xl">
      <button class="box-shadow" w:flex="~ col" w:justify="center" w:align="items-center" @click.prevent="openAddModal('text')">
        <PostTextIcon w:w="8" w:h="8" w:m="b-2" />
        <span w:display="block" w:text="center">{{ $t('post.types.text.button') }}</span>
      </button>
      <button class="box-shadow" w:flex="~ col" w:justify="center" w:align="items-center" @click.prevent="openAddModal('image')">
        <PostImageIcon w:w="8" w:h="8" w:m="b-2" />
        <span w:display="block" w:text="center">{{ $t('post.types.image.button') }}</span>
      </button>
      <button class="box-shadow" w:flex="~ col" w:justify="center" w:align="items-center" @click.prevent="openAddModal('poll')">
        <PostPollIcon w:w="8" w:h="8" w:m="b-2" />
        <span w:display="block" w:text="center">{{ $t('post.types.poll.button') }}</span>
      </button>
      <button class="box-shadow" w:flex="~ col" w:justify="center" w:align="items-center" @click.prevent="openAddModal('audio')">
        <PostAudioIcon w:w="8" w:h="8" w:m="b-2" />
        <span w:display="block" w:text="center">{{ $t('post.types.audio.button') }}</span>
      </button>
      <button class="box-shadow" w:flex="~ col" w:justify="center" w:align="items-center" @click.prevent="openAddModal('video')">
        <PostVideoIcon w:w="8" w:h="8" w:m="b-2" />
        <span w:display="block" w:text="center">{{ $t('post.types.video.button') }}</span>
      </button>
      <button class="box-shadow" w:flex="~ col" w:justify="center" w:align="items-center" @click.prevent="openAddModal('playlist')">
        <PostPlaylistIcon w:w="8" w:h="8" w:m="b-2" />
        <span w:w:display="block" w:text="center">{{ $t('post.types.playlist.button') }}</span>
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
    PostPlaylistIcon
  },
  emits: [
    'openAddModal'
  ],
  setup(_, context) {
    const openModal = ref('')

    function openAddModal($type) {
      context.emit('openAddModal', $type)
    }

    return {
      openModal,
      openAddModal
    }
  },
})
</script>

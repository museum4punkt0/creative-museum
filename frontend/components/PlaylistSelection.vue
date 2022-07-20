<template>
  <div>
    <div v-show="step === 1">
      <div w:p="6" class="page-header">
        <a @click="closeModal" class="back-btn">Zu Playlist hinzufügen</a>
      </div>
      <div
        class="box-shadow-mobile"
        w:pos="relative"
        w:m="6 lg:0"
        w:p="6"
        w:text="center"
      >
          <div v-if="user.playlists.length > 0">
            <div v-for="(item, key) in user.playlists" @click="$emit('selectPlaylist', item.id)">
              <span>{{ item.title }}</span>
            </div>
          </div>
          <div>
            <a @click="createPlaylist">{{ $t('post.actions.playlist.createNew.cta') }}</a>
          </div>
        </div>
    </div>
    <div v-show="step === 2">
        <input v-model="newPlaylistName" :placeholder="$t('post.actions.playlist.createNew.placeholder')" />

        <button @click="createPlaylistWithPost">{{ $t('post.actions.playlist.createNew.submitButton') }}</button>
    </div>
  </div>
</template>

<script>
import { defineComponent, ref, useStore, computed } from '@nuxtjs/composition-api'
import Logo from '@/assets/images/logo.svg?inline'

export default defineComponent({
  components: {
    Logo,
  },
  emits:[
    'closeModal',
    'selectPlaylist'
  ],
  setup(_, context) {

    const step = ref(1)
    const store = useStore()
    const user = computed(() => store.state.auth.user);

    const newPlaylistName = ref('')

    function finish() {
      context.emit('closeModal')
    }

    function closeModal() {
      context.emit('closeModal')
    }

    function createPlaylist() {
      step.value = 2
    }

    function createPlaylistWithPost() {
      context.emit('createPlaylist', newPlaylistName.value)
    }

    return {
      finish,
      closeModal,
      step,
      user,
      createPlaylist,
      newPlaylistName,
      createPlaylistWithPost
    }
  },
})
</script>

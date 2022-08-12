<template>
  <div w:flex="~ col 1">
    <div v-show="step === 1" w:flex="~ col 1">
      <div v-if="headline" w:p="6" class="page-header">
        <a class="back-btn" @click="backLink">{{ $t('playlist.addTo') }}</a>
      </div>
      <div
        w:p="6"
        w:grid="~ cols-2 gap-6"
        w:max-h="lg"
        w:overflow="y-auto"
        w:overscroll="y-auto"
      >
        <button
          v-for="(item, key) in user.playlists"
          :key="key"
          class="btn-primary"
          @click="$emit('selectPlaylist', item.id)"
        >
          <span w:h="30" w:flex="~ col" w:align="center" w:justify="center">
            {{ item.title }}
          </span>
        </button>
        <button v-if="addButton" class="btn-primary" @click.prevent="step = 2">
          <span
            w:h="30"
            w:flex="~ col"
            w:align="center items-center"
            w:justify="center"
          >
            <span
              class="add-btn-inline"
              w:display="block"
              w:text="center"
              w:m="y-4"
            ></span>
            {{ $t('playlist.new') }}
          </span>
        </button>
      </div>
    </div>
    <div v-show="step === 2" w:flex="~ col 1" w:justify="between">
      <div>
        <div w:p="6" class="page-header">
          <a class="back-btn" @click="backLink">{{
            $t('playlist.createNew')
          }}</a>
        </div>
        <div w:p="6">
          <input
            v-model="newPlaylistName"
            class="input-text"
            :placeholder="$t('post.actions.playlist.createNew.placeholder')"
          />
        </div>
      </div>
      <div w:p="6" w:mt="auto">
        <button class="btn-primary" w:w="full" @click="createPlaylistWithPost">
          {{ $t('post.actions.playlist.createNew.submitButton') }}
        </button>
      </div>
    </div>
  </div>
</template>

<script>
import {
  defineComponent,
  ref,
  useStore,
  computed,
} from '@nuxtjs/composition-api'

export default defineComponent({
  props: {
    headline: {
      type: Boolean,
      required: false,
      default: true,
    },
    addButton: {
      type: Boolean,
      required: false,
      default: true,
    },
  },
  emits: ['closeModal', 'selectPlaylist'],
  setup(_, context) {
    const step = ref(1)
    const store = useStore()
    const user = computed(() => store.state.auth.user)

    const newPlaylistName = ref('')

    function finish() {
      context.emit('closeModal')
    }

    function backLink() {
      if (step.value > 1) {
        step.value--
      } else {
        context.emit('closeModal')
      }
    }

    function createPlaylistWithPost() {
      context.emit('createPlaylist', newPlaylistName.value)
    }

    return {
      finish,
      backLink,
      step,
      user,
      newPlaylistName,
      createPlaylistWithPost,
    }
  },
})
</script>

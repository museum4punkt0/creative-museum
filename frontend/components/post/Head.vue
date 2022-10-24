<template>
  <div>
    <div v-if="post.author" class="flex flex-row justify-between">
      <NuxtLink
        :to="
          $auth.loggedIn && post.author.uuid === $auth.user.uuid
            ? localePath('/user/profile')
            : localePath(`/user/${post.author.uuid}`)
        "
        class="flex flex-row focus:outline-none group"
      >
        <UserProfileImage :user="post.author" class="mr-4" :class="post.type !== 'playlist' ? 'group-focus-visible:(ring-$highlight ring-2)' :`group-focus-visible:(ring-${textColor} ring-2)`" />
        <div class="flex flex-col">
          <span class="text-lg" :class="post.type !== 'playlist' ? 'group-focus-visible:(text-$highlight)' : `group-focus-visible:(text-${textColor})`">{{ $userName(post.author) }}</span>
          <span
            :class="post.type !== 'playlist' ? 'text-$highlight' : ''"
            class="text-sm mt-1"
            >{{
              $dayjs.duration($dayjs().diff($dayjs(post.created))).days() > 2
                ? $dayjs(post.created).format( $t('dateFormat') )
                : $dayjs(post.created).locale($i18n.locale).fromNow()
            }}</span
          >
        </div>
      </NuxtLink>
      <button class="focus:none" @click.prevent="onShowAdditionalOptions">
        <UtilitiesThreeDots
          class="cursor-pointer"
          :text-color="post.type === 'playlist' ? textColor : 'white'"
        />
      </button>
    </div>
    <transition
      enter-active-class="duration-300 ease-out -bottom-full lg:opacity-0 lg:bottom-auto"
      enter-to-class="bottom-0 lg:bottom-auto lg:opacity-100"
      leave-active-class="duration-200 ease-in"
      leave-class="bottom-0 lg:bottom-auto lg:top-1/2 lg:opacity-100"
      leave-to-class="bottom-full lg:bottom-auto lg:opacity-0"
    >
      <component
        :is="`Utilities${modalType}`"
        v-if="showAdditionalOptions"
        class="flex flex-col h-full"
        :closable="modalType === 'SlideUp' ? true : false"
        @closeModal="showAdditionalOptions = false"
      >
        <div v-if="!additionalPage" class="flex flex-col p-6 mr-12">
          <h3 class="mb-6">{{ $t('post.moreActions') }}</h3>
          <ul class="text-base">
            <li class="my-6">
              <button
                v-if="!post.bookmarked"
                @click="addOrRemoveBookmark(post.id)"
              >
                {{ $t('post.actions.addBookmark') }}
              </button>
            </li>
            <li class="my-6">
              <button
                v-if="post.bookmarked"
                @click="addOrRemoveBookmark(post.id)"
              >
                {{ $t('post.actions.removeBookmark') }}
              </button>
            </li>
            <li class="my-6">
              <button
                class="block btn-right"
                @click="openAwardAssignModal"
              >
                {{ $t('post.actions.assignAward') }}
              </button>
            </li>
            <li class="my-6">
              <button
                class="block btn-right"
                @click="openPlaylistSelectionModal"
              >
                {{ $t('post.actions.addToPlaylist') }}
              </button>
            </li>
            <li
              v-if="
                $auth.loggedIn &&
                $auth.user &&
                $auth.user.uuid === post.author.uuid
              "
              class="my-6"
            >
              <button class="block" @click="showDeleteDialog">
                {{ $t('post.actions.delete.button') }}
              </button>
            </li>
            <li class="my-6" v-if="isMobile">
              <button class="block" @click.prevent="shareLinkToSM">
                {{ $t('post.actions.shareLink.button') }}
              </button>
            </li>
            <li class="my-6">
              <button
                v-clipboard="shareLink"
                v-clipboard:success="linkCopiedSuccess"
                class="block"
              >
                <span v-if="linkCopied">{{
                  $t('post.actions.copyLink.success')
                }}</span>
                <span v-else>{{ $t('post.actions.copyLink.button') }}</span>
              </button>
              <input v-model="shareLink" type="hidden" />
            </li>
          </ul>
        </div>
        <div v-if="additionalPage" class="flex flex-col flex-1 items-stretch">
          <div
            v-if="additionalPageContent === 'playlistSelection'"
            class="flex flex-col flex-1 items-stretch"
          >
            <PlaylistSelection
              class="flex flex-col flex-1 items-stretch"
              @closeModal="additionalPage = false"
              @createPlaylist="addPostToNewPlaylist"
              @selectPlaylist="addPostToPlaylist"
            />
          </div>
          <div
            v-if="additionalPageContent === 'awardAssign'"
            class="flex flex-col flex-1 items-stretch"
          >
            <AwardAssign
              :post="post"
              class="flex flex-col flex-1 items-stretch"
              @closeModal="additionalPage = false"
            />
          </div>
          <div
            v-if="additionalPageContent === 'deleteDialog'"
            class="flex flex-col flex-1 h-full justify-between"
          >
            <div>
              <p class="m-6 text-lg">
                {{ $t('post.actions.delete.confirmation') }}
              </p>
            </div>
            <div class="mx-6 mb-6">
              <button
                class="btn-primary bg-$highlight text-$highlight-contrast border-$highlight w-full mb-4"
                @click.prevent="deletePost"
              >
                {{ $t('post.actions.delete.button') }}
              </button>
              <button
                class="btn-outline w-full"
                @click.prevent="
                  additionalPageContent = ''
                  additionalPage = false
                "
              >
                {{ $t('close') }}
              </button>
            </div>
          </div>
        </div>
      </component>
    </transition>
  </div>
</template>
<script>
import {
  defineComponent,
  ref,
  computed,
  useContext,
  useStore,
} from '@nuxtjs/composition-api'
import { postApi } from '@/api/post'

export default defineComponent({
  props: {
    post: {
      type: Object,
      required: true,
    },
    textColor: {
      type: String,
      required: true,
    },
  },
  emits: ['toggleBookmarkState', 'postDeleted'],
  setup(props, context) {
    const {
      toggleBookmark,
      addToPlaylist,
      createPlaylistWithPost,
      deletePostById,
    } = postApi()

    const store = useStore()
    const { $config, $auth } = useContext()

    const showAdditionalOptions = ref(false)
    const additionalPage = ref(false)
    const additionalPageContent = ref('')
    const linkCopied = ref(false)

    const modalType = computed(() => {
      return additionalPage.value ? 'Modal' : 'SlideUp'
    })

    const shareLink = computed(() => {
      return `${$config.baseURL}/posts/${props.post.id}`
    })

    async function addOrRemoveBookmark(postId) {
      await toggleBookmark(postId)
      context.emit('toggleBookmarkState', postId)
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
      createPlaylistWithPost(props.post.id, playlistName).then(function () {
        $auth.fetchUser()
        additionalPage.value = false
        showAdditionalOptions.value = false
      })
    }

    function openAwardAssignModal() {
      additionalPageContent.value = 'awardAssign'
      additionalPage.value = true
    }

    function onShowAdditionalOptions() {
      if (!$auth.loggedIn) {
        store.dispatch('showLogin')
      } else {
        showAdditionalOptions.value = true
      }
    }

    function showDeleteDialog() {
      additionalPageContent.value = 'deleteDialog'
      additionalPage.value = true
    }

    async function deletePost() {
      await deletePostById(props.post.id)
      context.emit('postDeleted')
      additionalPageContent.value = ''
      additionalPage.value = false
    }

    function shareLinkToSM() {
      navigator.share({
        url: shareLink.value,
      })
    }

    function linkCopiedSuccess() {
      linkCopied.value = true
    }

    function checkUserAgent() {
      /* eslint-disable */
      var check = false;
      (function(a) {
        if (
          /(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows ce|xda|xiino/i.test(
            a
          ) ||
          /1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i.test(
            a.substr(0, 4)
          )
        )
          check = true;
      })(navigator.userAgent || navigator.vendor || window.opera);
      return check;
    }

    const isMobile = checkUserAgent();

    return {
      showAdditionalOptions,
      additionalPage,
      additionalPageContent,
      modalType,
      shareLink,
      linkCopied,
      showDeleteDialog,
      addOrRemoveBookmark,
      openPlaylistSelectionModal,
      addPostToPlaylist,
      addPostToNewPlaylist,
      openAwardAssignModal,
      onShowAdditionalOptions,
      deletePost,
      linkCopiedSuccess,
      shareLinkToSM,
      isMobile,
    }
  },
})
</script>

<template>
  <div :id="`comment-${comment.id}`" v-if="comment" class="relative text-xs mt-4">
    <span v-if="$auth && $auth.user && ($auth.user.uuid === comment.author.uuid)" class="w-5 h-5 p-2 absolute top-1 right-1 cursor-pointer" role="button"><TrashIcon class="w-3 h-3" :class="postType === 'playlist' ? 'fill-$highlight-contrast' : 'fill-white'" @click.stop="showDeleteModal = true" /></span>
    <strong class="block">{{ $userName(comment.author) }}</strong>
    <div class="break-words" :class="$auth && $auth.user && ($auth.user.uuid === comment.author.uuid) ? 'mr-6' : ''" v-html="comment.body" />
    <span
      class="block"
      :class="postType !== 'playlist' ? 'text-$highlight' : ''"
      >{{
        $dayjs.duration($dayjs().diff($dayjs(comment.created))).days() > 2
          ? $dayjs(comment.created).format( $t('dateFormat') )
          : $dayjs(comment.created).locale($i18n.locale).fromNow()
      }}</span
    >
    <UtilitiesModal
        v-if="showDeleteModal"
        class="flex flex-col h-full text-base"
        :closable="true"
        tabindex="0"
        @closeModal="showDeleteModal = false"
      >
      <div
        class="flex flex-col flex-1 h-full justify-between"
      >
        <div>
          <div class="page-header px-6">
            <button class="back-btn" @click="additionalPage = false">{{ $t('post.actions.delete.headline') }}</button>
          </div>
          <div class="box-shadow-mobile relative m-6 lg:m-0 p-6">
            {{ $t('post.actions.delete.confirmation') }}
          </div>
        </div>
        <div class="mx-6 mb-6">
          <button
            class="btn-primary bg-$highlight text-$highlight-contrast border-$highlight w-full mb-4"
            @click.prevent="deleteComment"
          >
            {{ $t('post.actions.delete.button') }}
          </button>
          <button
            class="btn-outline w-full"
            @click.prevent="showDeleteModal = false"
          >
            {{ $t('close') }}
          </button>
        </div>
      </div>
    </UtilitiesModal>
  </div>
</template>
<script>
import { defineComponent, ref } from '@nuxtjs/composition-api'
import TrashIcon from '@/assets/icons/trash.svg?inline'
import { postApi } from '@/api/post'

export default defineComponent({
  components: {
    TrashIcon
  },
  props: {
    postType: {
      type: String,
      default: '',
    },
    comment: {
      type: [Object, Array],
      default: () => {},
    },
  },
  emits: [
    'refreshCommentsByPosts',
    'commentDeleted'
  ],
  setup(props, context) {

    const showDeleteModal = ref(false)
    const { deleteCommentById } = postApi()

    async function deleteComment() {
      showDeleteModal.value = false
      await deleteCommentById(props.comment.id).then(() => {
        context.emit('commentDeleted', props.comment.id)
      })
    }

    return {
      showDeleteModal,
      deleteComment
    }

  }
})
</script>

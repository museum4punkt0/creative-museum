<template>
  <div>
    <button
      class="cursor-pointer text-sm focus:outline-none"
      :class="post.type !== 'playlist' ? 'text-$highlight focus-visible:text-white' : 'text-$highlight-contrast focus-visible:(text-contrast underline)'"
      @click.prevent="
        !showComments
          ? fetchComments()
          : (showComments = showCommentForm = false)
      "
    >
      <ArrowIcon
        v-if="post.campaign.active || !post.campaign.closed || post.commentCount > 0"
        class="relative w-3 mr-0.5 inline-block transform-gpu"
        :class="
          showComments || showCommentForm ? 'transform-gpu rotate-180' : ''
        "
      />
      <span
        v-if="post.comments && post.commentCount > 0"
        class="inline-block mt-4"
      >
        {{
          !showComments
            ? $t('post.showComments', { count: post.commentCount })
            : $t('post.hideComments', { count: post.commentCount })
        }}</span
      >
      <span
        v-else-if="post.campaign.active || !post.campaign.closed"
        class="inline-block mt-4"
        @click.prevent="showCommentForm = !showCommentForm"
        >{{ $t('post.postComment') }}</span
      >
    </button>

    <div v-if="(comments && showComments) || showCommentForm" class="relative">
      <div v-if="comments && showComments" aria-expanded="true">
        <h3 class="sr-only">{{ $t('post.postComments') }}</h3>
        <ul>
          <li v-for="(comment, key) in comments" :key="key">
            <PostCommentItem :comment="comment" :post-type="post.type" />
          </li>
        </ul>
      </div>
      <form
        v-if="(post.campaign.active || !post.campaign.closed) && showCommentForm"
        class="sticky lg:static bottom-0 left-0 right-0 pt-4 pb-4 px-4 lg:(px-0 pb-0) -mb-10 -mr-10 -ml-10 lg:(mb-0 mr-0 ml-0)"
        @submit.prevent="submitComment"
      >
        <div class="container lg:container-none relative">
          <div v-if="!$auth.loggedIn" role="button" class="absolute t-0 l-0 b-0 r-0 w-full h-full z-10" @click="showLoginIfNotLoggedIn"></div>
          <UtilitiesRichTextEditorComments
            v-model="commentBody"
            class="input-text px-4 py-2 pr-8 text-white text-base resize-none z-0"
            :menubar="false"
            :model-value="commentBody"
            :placeholder="$t('post.commentPlaceholder')"
            @keydown.enter.prevent="submitComment"
            @submitForm="submitComment"
            @update:modelValue="updateModelValue"
            @click.prevent="showLoginIfNotLoggedIn"
          ></UtilitiesRichTextEditorComments>
          <button
            class="absolute w-3 right-3 top-2.5 max-h-3xl transform-gpu rotate-180 text-white/50 focus:outline-none focus-visible:text-white"
            :aria-label="$t('post.postCommentSend')"
            @click.prevent="submitComment"
          >
            <ArrowIcon />
          </button>
        </div>
      </form>
    </div>
    <div v-else-if="post.comments && !showComments">
      <h3 class="sr-only">{{ $t('post.postComments') }}</h3>
      <ul>
        <li v-for="(comment, key) in post.comments" :key="key">
          <PostCommentItem :comment="comment" :post-type="post.type" />
        </li>
      </ul>
    </div>
  </div>
</template>
<script>
import {
  defineComponent,
  ref,
  useContext,
  useStore,
} from '@nuxtjs/composition-api'
import { TextareaAutogrowDirective } from 'vue-textarea-autogrow-directive'
import { postApi } from '@/api/post'
import ArrowIcon from '@/assets/icons/arrow.svg?inline'

export default defineComponent({
  components: {
    ArrowIcon,
  },
  directives: {
    autogrow: TextareaAutogrowDirective,
  },
  props: {
    post: {
      type: Object,
      required: true,
    },
  },
  emits: ['commentsLoaded'],
  setup(props, context) {
    const comments = ref([])
    const newComments = ref([])
    const showComments = ref(false)
    const showCommentForm = ref(false)
    const commentBody = ref('')
    const { $auth, i18n } = useContext()
    const store = useStore()

    const { fetchPostsByPost, submitCommentByPost } = postApi()

    async function fetchComments() {
      comments.value = await fetchPostsByPost(props.post.id)
      showCommentForm.value = true
      showComments.value = true
    }

    async function submitComment() {
      if ($auth.loggedIn) {
        await submitCommentByPost(
          props.post.id,
          commentBody.value,
          props.post.campaign.id
        ).then(function () {
          commentBody.value = ''
          fetchComments()
          context.emit('commentsLoaded', props.post.id)
          store.dispatch('currentAlert', i18n.t('alert.commentSubmitted'))
        })
        $auth.fetchUser()
      } else {
        store.dispatch('showLogin')
      }
    }

    function showLoginIfNotLoggedIn() {
      if (!$auth.loggedIn) {
        store.dispatch('showLogin')
      }
    }

    function updateModelValue(content) {
      commentBody.value = content.text
    }

    return {
      comments,
      newComments,
      showComments,
      showCommentForm,
      commentBody,
      store,
      fetchComments,
      submitComment,
      showLoginIfNotLoggedIn,
      updateModelValue
    }
  },
})
</script>

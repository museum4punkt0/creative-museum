<template>
  <div>
    <div
      v-if="editor && menubar"
      class="float-right relative -top-3 p-3 rounded-b-xl bg-grey box-shadow-bottom tiptap-menu"
    >
      <button class="btn btn-outline inline-block py-1 text-xs font-bold" :class="{ 'bg-$highlight border-$highlight': editor.isActive('bold') }" @click="editor.chain().focus().toggleBold().run()">
        B
      </button>
      <button class="btn btn-outline inline-block py-1 text-xs italic" :class="{ 'bg-$highlight border-$highlight': editor.isActive('italic') }" @click="editor.chain().focus().toggleItalic().run()">
        I
      </button>
      <button class="btn btn-outline inline-block py-1 text-xs line-through" :class="{ 'bg-$highlight border-$highlight': editor.isActive('strike') }" @click="editor.chain().focus().toggleStrike().run()">
        S
      </button>
    </div>
    <editor-content :editor="editor" />
  </div>
</template>

<script>
import { Editor, EditorContent } from '@tiptap/vue-2'
import { StarterKit } from '@tiptap/starter-kit'
import { Placeholder } from '@tiptap/extension-placeholder'
import { CharacterCount } from '@tiptap/extension-character-count'
import { CustomMention } from '@/utilities/CustomMention'

import userSearch from '@/utilities/userSearch'

export default {
  components: {
    EditorContent,
  },
  props: {
    modelValue: {
      type: String,
      default: '',
    },
    limit: {
      type: Number,
      default: 1000
    },
    menubar: {
      type: Boolean,
      default: true
    },
    placeholder: {
      type: String,
      required: false,
      default: function() {
        this.$t('post.placeholder.body')
      }
    }
  },

  emits: ['update:modelValue'],

  data() {
    return {
      editor: null,
    }
  },

  watch: {
    modelValue(value) {
      const isSame = this.editor.getHTML() === value
      if (isSame) {
        return
      }

      this.editor.commands.setContent(value, false)
    },
  },

  mounted() {
    this.editor = new Editor({
      extensions: [
        StarterKit,
        Text,
        CharacterCount.configure({
          mode: 'nodeSize',
          limit: this.limit,
        }),
        Placeholder.configure({
          placeholder: this.placeholder,
        }),
        CustomMention.configure({
          HTMLAttributes: {
            class: 'mention',
          },
          suggestion: userSearch,
        }),
      ],
      editorProps: {
        attributes: {
          class: 'richtext prose prose-sm sm:prose lg:prose-lg xl:prose-2xl mx-auto focus:outline-none lg:max-h-2xl overflow-scroll scrollbar-hide'
        }
      },
      content: this.modelValue,
      onUpdate: () => {
        this.$emit('update:modelValue', { text: this.editor.getHTML(), count: this.editor.storage.characterCount.characters()})
      },
    })
  },

  beforeUnmount() {
    this.editor.destroy()
  },
}
</script>
<style lang="scss">
.ProseMirror p.is-editor-empty:first-child::before {
  content: attr(data-placeholder);
  float: left;
  color: #8b92a0;
  pointer-events: none;
  height: 0;
}
</style>
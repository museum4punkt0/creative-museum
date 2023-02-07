<template>
  <div>
    <editor-content :editor="editor" />
  </div>
</template>

<script>
import { Editor, EditorContent } from '@tiptap/vue-2'
import { StarterKit } from '@tiptap/starter-kit'
import { CustomMention } from '@/utilities/CustomMention'
import { Placeholder } from '@tiptap/extension-placeholder'
import { CharacterCount } from '@tiptap/extension-character-count'

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
        handleKeyDown: (_view, e) => {
          if (e.code === 'Enter' && e.shiftKey === true) {
            this.$emit('submitForm')
          };
        },
        attributes: {
          class: 'richtext prose prose-sm sm:prose lg:prose-lg xl:prose-2xl mx-auto focus:outline-none lg:max-h-2xl overflow-scroll'
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
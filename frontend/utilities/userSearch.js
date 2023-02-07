import { VueRenderer } from '@tiptap/vue-2'
import tippy from 'tippy.js'

import MentionList from './MentionList.vue'
export default {
  items: async ({ query }) => {
      if (process.client) {
        const response = await window.$nuxt.$axios.get(`users?and[or][][fullName]=${query}&and[or][][email]=${query}&and[or][][username]=${query}&and[deleted]=0`, {
          headers: {
            'Accept': 'application/json'
          }
        })
        const output = response.data.map(user => {
          return {
              'id': user.uuid,
              'label': user.username
          }
        });
        return output.slice(0, 5)
      }
  },
  render: () => {
    let component
    let popup
    return {
      onStart: props => {
        component = new VueRenderer(MentionList, {
          // using vue 2:
          parent: this,
          propsData: props,
        })

        if (!props.clientRect) {
          return
        }

        popup = tippy('body', {
          getReferenceClientRect: props.clientRect,
          appendTo: () => document.body,
          content: component.element,
          showOnCreate: true,
          interactive: true,
          trigger: 'manual',
          placement: 'bottom-start',
        })
      },

      onUpdate(props) {
        component.updateProps(props)

        if (!props.clientRect) {
          return
        }

        popup[0].setProps({
          getReferenceClientRect: props.clientRect,
        })
      },

      onKeyDown(props) {
        console.log(props)
        if (props.event.key === 'Escape') {
          popup[0].hide()

          return true
        }

        return component.ref?.onKeyDown(props)
      },

      onExit() {
        popup[0].destroy()
        component.destroy()
      },
    }
  }
}
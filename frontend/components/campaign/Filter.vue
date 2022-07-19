<template>
  <div>
    <div w:flex="~ col">
      <button w:px="2" w:py="1" w:mb="3" w:rounded="full" w:align="self-start" w:text="sm" w:border="1 white" class="btn-outline">
        Neuste
      </button>
      <button w:px="2" w:py="1" w:mb="3" w:rounded="full" w:align="self-start" w:text="sm" w:border="1 white" class="btn-outline">
        Relevant
      </button>
      <button w:px="2" w:py="1" w:mb="3" w:rounded="full" w:align="self-start" w:text="sm" w:border="1 white" class="btn-outline">
        Kontrovers
      </button>
      <DropDown :options="['Wie krass!', 'Super informativ!', 'Du erhellst meinen Tag!', 'Hab ich noch nie gehört.', 'Wie traurig.']" label="Feedback" />
      <button w:px="2" w:py="1" w:mb="3" w:rounded="full" w:align="self-start" w:text="sm" w:border="1 white" class="btn-outline">
        Playlist
      </button>
    </div>
  </div>
</template>
<script>
import { defineComponent } from '@nuxtjs/composition-api'

export default defineComponent({
  props: {
    campaign: {
      type: Object,
      required: true
    }
  },
  setup() {

  },
})
</script>

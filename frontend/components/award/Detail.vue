<template>
  <div>
    <div class="page-header p-6">
      <a class="back-btn" @click.prevent="$emit('closeAwardDetail')">{{
        $t('awards.detailHeadline')
      }}</a>
    </div>
    <div
      class="box-shadow-mobile relative m-6 lg:m-0 p-6 text-center"
    >
      <img
        v-if="award.picture"
        :src="`${backendUrl}/${award.picture.contentUrl}`"
        :alt="award.title"
        class="w-full max-w-32 h-auto"
      />
      <div class="text-left">
        <h1 class="text-2xl">{{ award.title }}</h1>
        <p>{{ award.description }}</p>
      </div>
    </div>
  </div>
</template>
<script>
import { defineComponent, useContext } from '@nuxtjs/composition-api'

export default defineComponent({
  props: {
    award: {
      type: Object,
      required: true
    }
  },
  emits: ['closeAwardDetail'],
  setup() {
    const context = useContext()

    return {
      backendUrl: context.$config.backendUrl
    }

  },
})
</script>

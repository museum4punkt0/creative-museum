<template>
  <div>
    <h1 v-if="error.statusCode === 404">Page not found</h1>
    <h1 v-else>An error occurred - {{ error.statusCode }}</h1>
    <NuxtLink to="/">Home page</NuxtLink>
    <OverlaysWrapper />
  </div>
</template>

<script>
export default {
  layout: 'error',
  props: {
    error: {
      type: Object,
      default: () => {},
    },
  },
  head() {
      return {
          htmlAttrs: {
              lang: this.$i18n.locale
          }
      }
  },
}
</script>

<template>
  <div>
    LoggedIn!
  </div>
</template>
<script>
import {defineComponent, useMeta, useContext} from '@nuxtjs/composition-api'

export default defineComponent({
  middleware: ['auth'],
  setup() {
    const { i18n } = useContext()
    useMeta({
      title: i18n.t('pages.login.title') + ' | ' + i18n.t('pageTitle')
    })
  },
  head: {}
})
</script>

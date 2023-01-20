<template>
  <div>
    <h1 class="text-2xl font-bold p-7">Login</h1>
  </div>
</template>
<script>
import {defineComponent, useContext, useMeta} from '@nuxtjs/composition-api'

export default defineComponent({
  setup() {
    const { $auth, i18n } = useContext()
    $auth.login()

    useMeta({
      title: i18n.t('pages.login.title') + ' | ' + i18n.t('pageTitle')
    })

  },
  head: {}
})
</script>

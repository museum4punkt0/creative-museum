<template>
  <div
    w:text="center"
  >
    <LogoFull
      w:display="inline-block"
      w:m="6"
    />
    <div><strong>&copy; 2022 Badisches Landesmuseum</strong></div>
  </div>
</template>
<script>
import { defineComponent, ref } from '@nuxtjs/composition-api'
import LogoFull from '@/assets/images/logo_full.svg?inline'

export default defineComponent({
  name: 'PageFooter',
  components: {
    LogoFull
  }
})
</script>
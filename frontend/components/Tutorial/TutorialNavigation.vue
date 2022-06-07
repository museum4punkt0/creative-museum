<template>
  <div
    w:bg="grey"
    w:p="6"
    w:m="t-10"
  >
    <NuxtLink class="btn-primary" w:m="b-3" :to="next" @click.native="goNext()"> {{ $t('next') }}</NuxtLink>
    <NuxtLink class="btn-outline" w:m="b-3" :to="prev"> {{ $t('prev') }}</NuxtLink>
    <NuxtLink class="btn-outline" to="/campaigns" @click.native="goNext(true)"> {{ $t('tutorial.skipTutorial') }}</NuxtLink>
  </div>
</template>
<script>
import { defineComponent, useStore, computed } from '@nuxtjs/composition-api'
import { userApi } from '@/api/user'

export default defineComponent({
  props: {
    next: {
      type: String,
      default: ''
    },
    prev: {
      type: String,
      default: ''
    },
    finish: {
      type: Boolean,
      default: false
    }
  },
  setup(props) {

    const store = useStore()

    const user = computed(() => store.state.auth.user);

    const { finishTutorial } = userApi()

    function goNext(finish = false) {
      if (props.finish === true || finish === true) {
        finishTutorial(user.value.uuid)
        return true
      } else {
        return true
      }
    }
    return {
      user,
      goNext
    }
  }
})
</script>

<template>
  <div class="loader flex flex-row justify-between" :class="small ? 'w-5 small' : 'w-20'">
    <div :class="small ? 'ball bg-white h-1 w-1 rounded-full' : 'ball bg-color1 h-5 w-5 rounded-full'"></div>
    <div :class="small ? 'ball bg-white h-1 w-1 rounded-full' : 'ball bg-color2 h-5 w-5 rounded-full'"></div>
    <div :class="small ? 'ball bg-white h-1 w-1 rounded-full' : 'ball bg-color3 h-5 w-5 rounded-full'"></div>
  </div>
</template>
<script>
  import { defineComponent } from '@nuxtjs/composition-api'
  export default defineComponent({
    props: {
      small: {
        type: Boolean,
        default: false
      }
    }
  })
</script>
<style lang="scss" scoped>

.ball {
  animation: bounce .5s alternate infinite;
}

.small {
  .ball {
    animation: bounce-small .5s alternate infinite;
  }
}

.ball:nth-child(2) {
  animation-delay: .16s;
}

.ball:nth-child(3) {
  animation-delay: .32s;
}

@keyframes bounce {
  from {
    tranform: scaleX(1.25);
  }
  to {
    transform: translateY(50px) scaleX(1)
  }
}
@keyframes bounce-small {
  from {
    tranform: scaleX(1.25);
  }
  to {
    transform: translateY(8px) scaleX(1)
  }
}
</style>

<template>
  <div class="loader flex flex-row justify-between w-20">
    <div class="ball bg-color1 h-5 w-5 rounded-full"></div>
    <div class="ball bg-color2 h-5 w-5 rounded-full"></div>
    <div class="ball bg-color3 h-5 w-5 rounded-full"></div>
  </div>
</template>
<style lang="scss" scoped>

.ball {
  animation: bounce .5s alternate infinite;
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
</style>
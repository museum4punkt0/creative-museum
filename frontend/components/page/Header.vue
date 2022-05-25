<template>

    <div
      ref="globalHeader"
      w:container="~"
      w:flex="~ row align-middle items-center"
      w:justify="between"
      w:align="items-center"
    >
      <Logo
        w:text="white/50"
        w:h="8 md:12"
        w:m="l-5 y-3"
      />
      <button
        class="addBtn"
        w:pos="relative"
        w:display="block"
        w:border="~ rounded-full white"
        w:h="6"
        w:w="6"
      />
      <div>
        <button
          w:m="r-5"
          @click.prevent="isMobileMenu = !isMobileMenu"
        >
          <span
            w:pointer-events="none"
            w:space="y-1"
          >
            <span
              w:display="block"
              w:w="w-6"
              w:h="px"
              w:bg="white"
              w:transition="all duration-300"
              :w:transform="isMobileMenu ? 'gpu rotate-45 origin-center translate-y-1.5' : ''"
            />
            <span
              w:display="block"
              w:w="w-6"
              w:h="px"
              w:bg="white"
              w:transition="all duration-500"
              :w:transform="isMobileMenu ? 'gpu translate-x-10 opacity-0' : ''"
            />
            <span
              w:display="block"
              w:w="w-6"
              w:h="px"
              w:bg="white"
              w:transition="all duration-300"
              :w:transform="isMobileMenu ? 'gpu -rotate-45 origin-center -translate-y-1' : ''"
            />
          </span>
        </button>
      </div>
    </div>
</template>
<script lang="ts" setup>
import Logo from '@/assets/images/logo.svg'
let isMobileMenu = ref(false)
</script>
<style lang="postcss" scoped>
.addBtn {
  box-shadow: 0 0 1px 0 theme('colors.white') inset, 0 0 1px 0 theme('colors.white');
  @apply
    before:(block content-[''] w-px h-4 bg-white absolute top-1/2 transform -translate-x-px left-1/2 -translate-y-1/2)
    after:(block content-[''] w-4 h-px bg-white absolute top-1/2 transform -translate-y-px left-1/2 -translate-x-1/2)
  ;
}
</style>
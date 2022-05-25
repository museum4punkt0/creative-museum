<template>

    <div
      ref="globalHeader"
      w:pos="relative"
      w:container="~"
      w:flex="~ row align-middle items-center"
      w:justify="between"
      w:align="items-center"
    >
      <Logo
        w:text="white/50"
        w:h="8 md:12"
        w:m="l-5 y-3"
        w:transition="scale duration-300 ease-in-out"
        w:transform="gpu hover:scale-125"
        w:cursor="pointer"
      />
      <button
        class="addBtn"
        w:pos="absolute"
        w:left="1/2"
        w:transform="-translate-x-1/2"
        w:display="block"
        w:border="~ rounded-full white"
        w:h="6"
        w:w="6"
      />
      <div
        w:flex="~ row"
        w:m="r-5"
        w:space="x-4"
        w:align="items-center"
      >
        <div
          w:flex="~ row"
          w:space="md:x-4"
          w:align="items-center"
        >
          <img
            src="/images/placeholder_profile.png"
            w:w="6"
            w:h="6"
            w:object="contain center"
            w:rounded="full"
          />
          <span
            w:text="sm overflow-ellipsis ..."
            w:display="hidden md:inline-block"
            w:overflow="hidden"
            w:min-w="24"
            w:max-w="32"
          >
            @Profilname
          </span>
        </div>
        <button
          w:transition="scale duration-300 ease-in-out"
          w:transform="gpu hover:scale-125"
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
    transform duration-200 ease-in-out
    before:(block content-[''] w-px h-4 bg-white absolute top-1/2 transform -translate-x-px  translate-x-[-.5px] left-1/2 -translate-y-1/2)
    after:(block content-[''] w-4 h-px bg-white absolute top-1/2 transform translate-y-[-.5px] left-1/2 -translate-x-1/2)
  ;
  &:hover {
    @apply rotate-90 scale-125;
  }
}
</style>
<template>
  <div>
    <div id="globalHeader" ref="globalHeader" w:pos="relative" w:container="~" w:flex="~ row" w:justify="between"
      w:align="items-center">
      <NuxtLink id="pageLogo" to="/">
        <Logo w:text="white/50 hover:$highlight" w:h="8 md:12" w:m="l-5 y-3" w:transition="all duration-300 ease-in-out"
          w:transform="gpu hover:scale-125" w:cursor="pointer" />
      </NuxtLink>
      <button v-show="isAddButtonVisible" class="add-btn" w:pos="absolute" w:left="1/2" w:transform="-translate-x-1/2"
        w:display="block" w:border="~ rounded-full white" w:h="6" w:w="6" />
      <div w:flex="~ row" w:m="r-5" w:space="x-4" w:align="items-center">
        <PageHeaderUserInfo />
        <button w:transition="scale duration-300 ease-in-out" w:transform="gpu hover:scale-125" w:h="6" w:w="6"
          :w:border="
            isMenuVisible
              ? '~ rounded-full white'
              : '~ rounded-full white transparent'
          " @click.prevent="isMenuVisible = !isMenuVisible">
          <span w:pointer-events="none" w:space="y-1" w:display="block" :w:m="isMenuVisible ? '-t-0.5' : ''">
            <span w:display="block" w:h="px" w:bg="white" w:transition="all duration-300" :w:transform="
                isMenuVisible
                  ? 'gpu rotate-45 origin-center translate-y-1.5 scale-x-75'
                  : ''
              " />
            <span w:display="block" w:h="px" w:bg="white" w:transition="all duration-500"
              :w:transform="isMenuVisible ? 'gpu translate-x-10 opacity-0' : ''" />
            <span w:display="block" w:h="px" w:bg="white" w:transition="all duration-300" :w:transform="
                isMenuVisible
                  ? 'gpu -rotate-45 origin-center -translate-y-1 scale-x-75'
                  : ''
              " />
          </span>
        </button>
      </div>
    </div>
    <div>
      <transition enter-active-class="duration-300 ease-out opacity-0" enter-to-class="opacity-100"
        leave-active-class="duration-200 ease-in" leave-class="opacity-100" leave-to-class="opacity-0">
        <div v-show="isMenuVisible" w:pos="absolute" w:top="16" w:left="0" w:right="0" w:p="t-20 b-10" w:min-h="sm"
          w:bg="grey" w:shadow="lg black/20" w:z="50">
          <div w:p="x-5" w:grid="lg:~ lg:cols-4" w:container="~">
            <div w:mb="10 lg:0">
              <button
                v-if="!$auth.loggedIn"
                w:text="white" w:flex="~ row" w:align="items-center" w:font="bold leading-loose"
                @click.prevent="login">
                <LoginIcon />
                <span>Login</span>
              </button>
              <button
                v-else
                w:text="white" w:flex="~ row" w:align="items-center" w:font="bold leading-loose"
                @click.prevent="logout">
                <LogoutIcon w:m="r-2" />
                <span>Logout</span>
              </button>
            </div>
            <div w:mb="10 lg:0">
              <p w:text="lg" w:font="bold leading-loose">Profil</p>
            </div>
            <div w:mb="10 lg:0">
              <p w:text="lg" w:font="bold leading-loose">Creative Museum</p>
            </div>
            <div w:mb="10 lg:0">
              <div w:flex="~ row" w:align="items-center" w:mb="4">
                <div class="toggle" w:flex="~ row" w:overflow="hidden">
                  <div class="toggle__item">
                    <input id="de" w:w="0" w:h="0" w:overflow="hidden" type="radio" value="de" name="language" checked>
                    <label for="de" w:px="3" w:display="inline-block" w:font="leading-loose" w:transition="background-color duration-300">DE</label>
                  </div>
                  <div class="toggle__item">
                    <input id="en" w:w="0" w:h="0" w:overflow="hidden" type="radio" value="en" name="language">
                    <label for="en" w:px="3" w:display="inline-block" w:font="leading-loose" w:transition="background-color duration-300">EN</label>
                  </div>
                </div>
                <p w:ml="4" w:font="leading-none">Deutsch</p>
              </div>
              <div w:flex="~ row" w:align="items-center" w:mb="4">
                <svg viewBox="0 0 24 25" fill="none" xmlns="http://www.w3.org/2000/svg" w:w="6" w:h="6" w:mr="4">
                  <g clip-path="url(#clip0_1109_17224)">
                    <path
                      d="M11.7512 8.68017C13.5091 8.68017 14.9341 7.25515 14.9341 5.49731C14.9341 3.73947 13.5091 2.31445 11.7512 2.31445C9.99337 2.31445 8.56836 3.73947 8.56836 5.49731C8.56836 7.25515 9.99337 8.68017 11.7512 8.68017Z"
                      stroke="white" stroke-miterlimit="10" stroke-linecap="round" />
                    <path
                      d="M5.19996 8.44615L11.5085 11.4462C11.662 11.5193 11.8299 11.5572 12 11.5572C12.17 11.5572 12.3379 11.5193 12.4914 11.4462L18.8 8.44615C18.8869 8.40473 18.983 8.38596 19.0791 8.39161C19.1753 8.39725 19.2685 8.42712 19.35 8.47844C19.4316 8.52975 19.4988 8.60085 19.5455 8.68512C19.5922 8.76939 19.6168 8.8641 19.6171 8.96044V18.8862C19.6191 18.998 19.5882 19.108 19.5283 19.2025C19.4683 19.2969 19.382 19.3717 19.28 19.4176L12.4685 22.4776C12.3212 22.5438 12.1615 22.5781 12 22.5781C11.8384 22.5781 11.6787 22.5438 11.5314 22.4776L4.71996 19.4176C4.6197 19.3725 4.53457 19.2995 4.47477 19.2073C4.41497 19.115 4.38304 19.0075 4.38281 18.8976V8.96044C4.38308 8.8641 4.40771 8.76939 4.4544 8.68512C4.50109 8.60085 4.56833 8.52975 4.64986 8.47844C4.7314 8.42712 4.82459 8.39725 4.92076 8.39161C5.01694 8.38596 5.11298 8.40473 5.19996 8.44615V8.44615Z"
                      stroke="white" stroke-miterlimit="10" stroke-linecap="round" />
                    <path d="M12 12.1094V22.1179" stroke="white" stroke-miterlimit="10" stroke-linecap="round" />
                  </g>
                  <defs>
                    <clipPath id="clip0_1109_17224">
                      <rect width="24" height="24" fill="white" transform="translate(0 0.560547)" />
                    </clipPath>
                  </defs>
                </svg>
                <span>Leichte Sprache</span>
              </div>
              <div w:flex="~ row" w:align="items-center" w:mb="4">
                <svg viewBox="0 0 24 25" fill="none" xmlns="http://www.w3.org/2000/svg" w:w="6" w:h="6" w:mr="4">
                  <g clip-path="url(#clip0_1109_17181)">
                    <path
                      d="M10.1654 17.1099L16.4511 19.4241C16.6977 19.4365 16.9413 19.3646 17.1417 19.2203C17.3421 19.0759 17.4875 18.8677 17.5539 18.6299C17.633 18.4046 17.6286 18.1584 17.5416 17.9361C17.4545 17.7138 17.2906 17.5301 17.0796 17.4184L10.7539 14.9927M9.55678 19.0441L15.5568 21.147C15.7654 21.1562 15.9717 21.0997 16.1466 20.9854C16.3214 20.871 16.4559 20.7047 16.5311 20.5099C16.6175 20.3179 16.64 20.1032 16.5953 19.8974C16.5505 19.6917 16.4409 19.5057 16.2825 19.367L10.1396 17.1127M10.7825 14.987L17.1425 17.4499C17.3699 17.5114 17.6121 17.4873 17.8228 17.3819C18.0335 17.2765 18.1982 17.0973 18.2854 16.8784C18.6054 16.0841 17.8311 15.6384 17.8311 15.6384L11.3396 12.8699H12.2654C12.2654 12.8699 13.3825 12.8413 13.3825 11.7527C13.3825 10.6641 12.2396 10.6098 12.2396 10.6098C12.2396 10.6098 7.32821 9.84699 4.88535 10.2013C3.65392 10.3813 2.12535 10.6098 1.74249 13.2813C1.20535 16.7727 2.77106 18.4784 3.62535 18.8956C4.95964 19.547 9.11964 20.7927 9.11964 20.7927L13.7882 22.4613C13.9847 22.4595 14.1751 22.393 14.33 22.2722C14.485 22.1513 14.5958 21.9828 14.6454 21.7927C14.6888 21.6124 14.6853 21.4241 14.6353 21.2455C14.5853 21.0669 14.4904 20.9042 14.3596 20.7727L9.55678 19.0584"
                      stroke="white" stroke-linecap="round" stroke-linejoin="round" />
                    <path
                      d="M13.8225 9.7801L18.8225 5.32582C18.9472 5.11516 18.9973 4.86862 18.9646 4.626C18.932 4.38338 18.8185 4.15885 18.6425 3.98868C18.4805 3.81547 18.2621 3.70566 18.0265 3.67895C17.7908 3.65225 17.5534 3.7104 17.3568 3.84296L12.231 8.27439M15.2425 11.2287L19.9282 6.91439C20.0343 6.73451 20.0815 6.52592 20.063 6.31787C20.0446 6.10983 19.9615 5.91279 19.8253 5.75439C19.6968 5.58743 19.518 5.46619 19.3153 5.40858C19.1126 5.35096 18.8968 5.36001 18.6996 5.43439L13.811 9.80582M16.571 12.4372L20.2482 9.10582C20.3355 8.93204 20.3637 8.73454 20.3286 8.54327C20.2936 8.35199 20.1971 8.17735 20.0539 8.04582C19.9175 7.92181 19.7517 7.83468 19.5722 7.79269C19.3927 7.75069 19.2055 7.75521 19.0282 7.80582L15.2425 11.2344M12.2396 8.23725L17.4082 3.78582C17.5633 3.61036 17.6488 3.38427 17.6488 3.1501C17.6488 2.91594 17.5633 2.68985 17.4082 2.51439C16.8568 1.85725 16.0996 2.33153 16.0996 2.33153L10.6339 6.74868L11.071 5.93153C11.071 5.93153 11.571 4.93153 10.611 4.4201C9.65105 3.90868 9.05962 4.89153 9.05962 4.89153C8.38226 5.98612 7.77901 7.12486 7.25391 8.3001"
                      stroke="white" stroke-linecap="round" stroke-linejoin="round" />
                  </g>
                  <defs>
                    <clipPath id="clip0_1109_17181">
                      <rect width="24" height="24" fill="white" transform="translate(0 0.560547)" />
                    </clipPath>
                  </defs>
                </svg>
                <span>Deutsche Gebärdensprache</span>
              </div>
            </div>
          </div>
        </div>
      </transition>
    </div>
  </div>
</template>
<script>
import { defineComponent, ref, useContext, useStore, computed } from '@nuxtjs/composition-api'
import Logo from '@/assets/images/logo.svg?inline'
import LoginIcon from '@/assets/icons/login.svg?inline'
import LogoutIcon from '@/assets/icons/logout.svg?inline'

export default defineComponent({
  name: 'PageHeader',
  components: {
    Logo,
    LoginIcon,
    LogoutIcon
  },
  setup() {
    const store = useStore()
    const context = useContext()

    const isMenuVisible = ref(false)

    function closeMenu() {
      isMenuVisible.value = false
    }

    function login() {
      context.$auth.login().then(closeMenu())
    }

    function logout() {
      context.$auth.logout().then(closeMenu())
    }

    return {
      isMenuVisible,
      isAddButtonVisible: computed(() => store.state.showAddButton),
      closeMenu,
      login,
      logout
    }
  },
})
</script>
<style lang="postcss" scoped>
.add-btn {
  box-shadow: 0 0 1px 0 theme('colors.white') inset,
    0 0 1px 0 theme('colors.white');
  @apply transform duration-200 ease-in-out -translate-x-1/2
    before:(block content-[''] w-px h-4 bg-white absolute top-1/2 transform -translate-x-px  translate-x-[-.5px] left-1/2 -translate-y-1/2)
    after:(block content-[''] w-4 h-px bg-white absolute top-1/2 transform translate-y-[-.5px] left-1/2 -translate-x-1/2);
  &:hover {
    @apply rotate-90 scale-125;
  }
}
</style>

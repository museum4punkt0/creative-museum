import { defineNuxtConfig } from 'nuxt3'
import svgLoader from "vite-svg-loader"

export default defineNuxtConfig({
  vite: {
    server: {
      hmr: {
        protocol: 'wss',
        clientPort: 4444
      }
    },
    plugins: [svgLoader()]
  },
  css: [
    '@/assets/css/main.scss',
    'virtual:windi.css',
    'virtual:windi-devtools',
  ],
  meta: {
    title: 'Creative Museum - Badisches Landesmuseum',
    charset: 'utf-8',
    viewport: 'width=device-width, initial-scale=1, shrink-to-fit=no',
    link: [
      { rel: 'icon', type: 'image/x-icon', href: '/icons/favicon.png' }
    ]
  },
  components: true,
  modules: [
    '@nuxtjs-alt/auth',
    '@nuxtjs-alt/axios',
    '@nuxtjs-alt/pinia',
    '@intlify/nuxt3',
    'nuxt-windicss',
  ],
  intlify: {
    vueI18n: {
      locale: 'de',
      localeDir: 'locales',
      availableLocales: ['de', 'en']
    }
  }
})

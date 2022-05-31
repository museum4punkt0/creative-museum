import 'dotenv/config'
import { defineNuxtConfig } from 'nuxt'
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
    meta: [
      { name: 'theme-color', content: '#ffdd67' }
    ],
    link: [
      { hid: 'icon', rel: 'icon', type: 'image/png', href: '/icons/logo_x32.png' },
      { hid: 'apple-touch-icon', rel: 'apple-touch-icon', href: '/icons/logo_x180.png' },
      { rel: 'manifest', href: '/manifest.json' }
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
  auth: {
    defaultStrategy: 'iam',
    // @ts-ignore
    redirect: {
      home: '/',
      logout: '/',
      login: '/verify',
    },
    strategies: {
      // @ts-ignore
      iam: {
        scheme: 'oauth2',
        endpoints: {
          authorization: 'https://identity-manager.ddev.site/authorize',
          token: 'https://identity-manager.ddev.site/token',
          userInfo: { url: '/user-info', baseURL: 'https://identity-manager.ddev.site/', method: 'POST' },
          logout: 'https://identity-manager.ddev.site/logout'
        },
        clientId: 'bdlm_cm',
        scope: ['default'],
        responseType: 'token',
        grantType: 'authorization_code',
        accessType: 'offline',
        redirectUri: 'https://creative-museum.ddev.site/verify',
        logoutRedirectUri: 'https://creative-museum.ddev.site/',
        state: 'UNIQUE_AND_NON_GUESSABLE',
        codeChallengeMethod: 'implicit',
      }
    }
  },
  postcss: {
    plugins: {
      cssnano: false
    }
  },
  intlify: {
    vueI18n: {
      locale: 'de',
      // @ts-ignore
      localeDir: 'locales',
      availableLocales: ['de', 'en']
    }
  }
})

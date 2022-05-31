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
  router: {
    middleware: ['auth']
  },
  auth: {
    globalMiddleware: false,
    strategies: {
      oauth2: {
        provider: 'oauth2',
        endpoints: {
          authorization: 'https://identity-manager.ddev.site/authorize',
          token: 'https://identity-manager.ddev.site/token',
          // userInfo: {url: 'https://identity-manager.ddev.site/user-info'},
          logout: 'https://identity-manager.ddev.site/logout'
        },
        token: {
          property: 'access_token',
          type: 'Bearer',
          maxAge: 60
        },
        user: {
          property: 'user',
        //  autoFetch: true
        },
        responseType: 'token',
        grantType: 'implicit',
        accessType: 'offline',
        redirectUri: 'https://creative-museum.ddev.site/verify',
        logoutRedirectUri: 'https://creative-museum.ddev.site/login',
        clientId: 'bdlm_cm',
        scope: ['default'],
        state: 'UNIQUE_AND_NON_GUESSABLE',
        codeChallengeMethod: 'S256',
        responseMode: '',
        acrValues: '',
        // autoLogout: false
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
      localeDir: 'locales',
      availableLocales: ['de', 'en']
    }
  }
})

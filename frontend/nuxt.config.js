import WindiCSSWebpackPlugin from 'windicss-webpack-plugin'
export default {
  head: {
    title: 'Creative Musuem - Badisches Landesmuseum',
    meta: [
      { charset: 'utf-8' },
      { name: 'viewport', content: 'width=device-width, initial-scale=1' },
      { hid: 'description', name: 'description', content: '' },
      { name: 'format-detection', content: 'telephone=no' },
    ],
    link: [{ rel: 'icon', type: 'image/x-icon', href: '/favicon.ico' }],
  },
  css: [
    '@/assets/css/main.scss',
    'virtual:windi.css'
  ],
  plugins: [],
  components: true,
  buildModules: [
    '@nuxt/typescript-build',
    '@nuxtjs/composition-api/module',
    '@nuxtjs/svg',
    'nuxt-windicss',
    'nuxt-webpack-optimisations',
    '@nuxt/postcss8'
  ],
  modules: [
    '@nuxtjs/axios',
    '@nuxtjs/auth-next',
    '@nuxtjs/pwa',
    '@nuxtjs/i18n'
  ],
  i18n: {
    locales: [
      { code: 'de', iso: 'de-DE', file: 'de.js', dir: 'ltr' },
      { code: 'en', iso: 'en-US', file: 'en.js', dir: 'ltr' },
    ],
    defaultLocale: 'de',
    vueI18n: {
      fallbackLocale: 'en',
    }
  },
  axios: {
    baseURL: 'https://api.creative-museum.ddev.site',
  },
  pwa: {
    manifest: {
      lang: 'de',
    },
  },
  auth: {
    defaultStrategy: 'iam',
    strategies: {
      iam: {
        scheme: 'oauth2',
        endpoints: {
          authorization: 'https://identity-manager.ddev.site/authorize',
          token: 'https://identity-manager.ddev.site/token',
          userInfo: 'https://identity-manager.ddev.site/user-info',
          logout: 'https://identity-manager.ddev.site/logout'
        },
        token: {
          property: 'access_token',
          type: 'Bearer'
        },
        user: {
          property: 'user'
        },
        responseType: 'token',
        grantType: 'authorization_code',
        accessType: undefined,
        redirectUri: 'https://creative-museum.ddev.site/login',
        logoutRedirectUri: '/',
        clientId: 'bdlm_cm',
        scope: ['default'],
        state: 'UNIQUE_AND_NON_GUESSABLE',
        codeChallengeMethod: 'S256',
        responseMode: 'token',
        acrValues: ''
      }
    }
  }
}

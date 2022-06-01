export default {
  // Global page headers: https://go.nuxtjs.dev/config-head
  head: {
    title: 'frontend',
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
    'virtual:windi.css',
    'virtual:windi-devtools'
  ],
  plugins: [],
  components: true,
  buildModules: [
    '@nuxt/typescript-build',
    '@nuxtjs/svg',
    'nuxt-windicss'
  ],
  modules: [
    '@nuxtjs/axios',
    '@nuxtjs/auth-next',
    '@nuxtjs/pwa'
  ],
  axios: {
    baseURL: '/',
  },
  pwa: {
    manifest: {
      lang: 'en',
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
        grantType: 'implicit',
        accessType: undefined,
        redirectUri: 'https://creative-museum.ddev.site/login',
        logoutRedirectUri: undefined,
        clientId: 'bdlm_cm',
        scope: ['default'],
        state: 'UNIQUE_AND_NON_GUESSABLE',
        codeChallengeMethod: 'S256',
        responseMode: 'token',
        acrValues: ''
      }
    }
  },
  build: {},
}

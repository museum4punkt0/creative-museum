export default {
  head: {
    title: 'Creative Museum - Badisches Landesmuseum',
    meta: [
      { charset: 'utf-8' },
      { name: 'viewport', content: 'width=device-width, initial-scale=1' },
      { hid: 'description', name: 'description', content: '' },
      { name: 'format-detection', content: 'telephone=no' },
    ],
    link: [
      { rel: 'icon', type: 'image/x-icon', href: '/favicon.ico' },
      {
        rel: 'preconnect',
        href: 'https://backend.creative-museum.ddev.site',
        crossorigin: 'use-credentials',
      },
    ],
  },
  css: [
    'virtual:windi-base.css',
    '@/assets/css/main.scss',
    'virtual:windi-components.css',
    'virtual:windi-utilities.css',
  ],
  plugins: [
    '~/plugins/api',
    { src: '~/plugins/vueRecord', mode: 'client' },
    { src: '~/plugins/progressBar', mode: 'client' },
  ],
  components: true,
  buildModules: [
    '@nuxt/typescript-build',
    '@nuxtjs/composition-api/module',
    '@nuxtjs/svg',
    'nuxt-windicss',
    'nuxt-webpack-optimisations',
    '@nuxt/postcss8',
  ],
  modules: [
    '@nuxtjs/axios',
    '@nuxtjs/auth-next',
    '@nuxtjs/pwa',
    '@nuxtjs/i18n',
    '@nuxtjs/dayjs',
    'nuxt-breakpoints',
  ],
  breakpoints: {
    sm: 640,
    md: 768,
    lg: 1024,
    xl: 1280,
    '2xl': 1536,
  },
  i18n: {
    langDir: 'locales',
    locales: [
      { code: 'de', iso: 'de-DE', file: 'de.json', dir: 'ltr' },
      { code: 'en', iso: 'en-US', file: 'en.json', dir: 'ltr' },
    ],
    defaultLocale: 'de',
    vueI18n: {
      fallbackLocale: 'de',
    },
  },
  dayjs: {
    locales: ['de', 'en'],
    defaultLocale: 'de',
    plugins: ['relativeTime', 'duration'],
  },
  axios: {
    baseURL: 'https://backend.creative-museum.ddev.site/v1/',
  },
  pwa: {
    meta: {
      charset: 'utf-8',
      author: 'Badisches Landesmuseum - Creative Museum',
      name: 'Creative Museum',
      nativeUI: true,
      mobileAppIOS: true,
      viewport: 'width=device-width, initial-scale=1, viewport-fit=cover',
    },
    manifest: {
      crossorigin: 'use-credentials',
      name: 'Creative Museum',
      short_name: 'Creative Museum',
      lang: 'de',
      background_color: '#2E2E2E',
      theme_color: '#2E2E2E',
      useWebmanifestExtension: true,
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
          userInfo: 'users/me',
          logout: 'https://identity-manager.ddev.site/logout',
        },
        token: {
          property: 'access_token',
          type: 'Bearer',
        },
        user: {
          property: false,
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
        acrValues: '',
      },
    },
  },
  telemetry: false,
  router: {
    middleware: ['user'],
  },
}

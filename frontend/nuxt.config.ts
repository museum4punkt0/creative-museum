export default {
  head: {
    title: 'Creative Museum - Badisches Landesmuseum',
    meta: [
      { charset: 'utf-8' },
      { name: 'viewport', content: 'width=device-width, initial-scale=1' },
      { hid: 'description', name: 'description', content: '' },
      { name: 'format-detection', content: 'telephone=no' },
    ],
    script: [
      {
        id: 'usercentrics-cmp',
        type: 'application/javascript',
        src: 'https://app.usercentrics.eu/browser-ui/latest/loader.js',
        'data-settings-id': process.env.USER_CENTRICS_ID,
        async: true,
      },
    ],
    link: [
      { rel: 'icon', type: 'image/x-icon', href: '/favicon.ico' },
      {
        rel: 'preconnect',
        href: process.env.BACKEND_URL,
        crossorigin: 'use-credentials',
      },
    ],
  },
  components: true,
  css: [
    'virtual:windi-base.css',
    '@/assets/css/main.scss',
    'virtual:windi-components.css',
    'virtual:windi-utilities.css',
  ],
  plugins: [
    '~/plugins/api',
    '~/plugins/user',
    { src: '~/plugins/updater', mode: 'client' },
    { src: '~/plugins/tooltip', mode: 'client' },
    { src: '~/plugins/clipboard', mode: 'client' }
  ],
  buildModules: [
    '@nuxt/typescript-build',
    '@nuxtjs/composition-api/module',
    '@nuxtjs/svg',
    'nuxt-windicss',
    '@nuxt/postcss8',
  ],
  build: {
    transpile: [
      'rxjs-interop'
    ]
  },
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
    baseURL: `${process.env.BACKEND_URL}/v1/`,
  },
  workbox: {
    cachingExtensions: '~plugins/workbox-range-request.js',
    runtimeCaching: [
      {
        urlPattern: `${process.env.BASE_URL}/login`,
        handler: 'networkOnly'
      },
      {
        urlPattern: `${process.env.BASE_URL}/verify`,
        handler: 'networkOnly'
      },
      {
        urlPattern: `${process.env.BACKEND_URL}/fileadmin/.*`,
        handler: 'StaleWhileRevalidate',
        method: 'GET',
        strategyOptions: {
          cacheName: 'assets-cache',
          maxAgeRecords: 24 * 60 * 60
        }
      }
    ]
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
          authorization: `${process.env.IAM_URL}/authorize`,
          token: `${process.env.IAM_URL}/token`,
          userInfo: 'users/me',
          logout: `${process.env.IAM_URL}/logout`,
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
        redirectUri: `${process.env.BASE_URL}/verify`,
        logoutRedirectUri: '/',
        clientId: 'bdlm_cm',
        scope: ['default'],
        state: 'UNIQUE_AND_NON_GUESSABLE',
        codeChallengeMethod: 'S256',
        responseMode: '',
        acrValues: '',
      },
    },
    redirect: {
      login: '/login',
      logout: '/',
      home: '/',
      callback: '/verify'
    },
  },
  router: {
    middleware: ['user'],
  },
  publicRuntimeConfig: {
    baseURL: process.env.BASE_URL,
    idpURL: process.env.IAM_URL,
    backendURL: process.env.BACKEND_URL,
    postsPerPage: 30,
  },
}

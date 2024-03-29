export default function ({ $axios, app }, inject) {
  const api = $axios.create()

  api.onRequest((config) => {
    if (config.method === 'patch') {
      config.headers['Content-Type'] = 'application/merge-patch+json'
    }
    config.headers.Accept = 'application/json'
    if (app.$auth.loggedIn) {
      const token = app.$auth.strategy.token.get()
      api.setToken(token)
    }
  })

  api.onResponse((res) => {
    return res.data
  })

  api.onError((error) => {
    return { error, data: {} }
  })

  inject('api', api)
}

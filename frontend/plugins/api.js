export default function ({ $axios, app }, inject) {
  const api = $axios.create()

  api.onRequest((config) => {
    if (config.method === 'patch') {
      config.headers['Content-Type'] = 'application/merge-patch+json'
    }
    config.headers.Accept = 'application/json'
  })

  api.onError((error) => {
    const code = parseInt(error.response && error.response.status)
    const errorText = code
      ? `A request failed with status code ${code}`
      : `A network error occurred`
    console.error(errorText)
  })

  api.onResponse((res) => {
    return res.data
  })

  inject('api', api)
}
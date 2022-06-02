export default function ({ $axios, app }, inject) {
  $axios.onRequest(
    (config) => {
      if (config.method === 'patch') {
          config.headers['Content-Type'] = 'application/merge-patch+json'
      }
      config.headers.Accept = 'application/json'
    }
  )
}
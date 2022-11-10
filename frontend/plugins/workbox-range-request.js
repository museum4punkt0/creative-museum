/* eslint-disable no-undef */
workbox.routing.registerRoute(
  /.*\.(mp4|webm)/,
  new workbox.strategies.CacheFirst({
    cacheName: 'videos',
    plugins: [
      new workbox.rangeRequests.RangeRequestsPlugin()
    ]
  }),
  'GET'
)

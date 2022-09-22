import { defineNuxtMiddleware } from '@nuxtjs/composition-api'
export default defineNuxtMiddleware((ctx) => {
  if (process.client) {
    document.documentElement.style.removeProperty('--highlight')

    document.documentElement.style.removeProperty('--highlight-contrast')
  }

  if (process.client && ctx.$auth.loggedIn) {
    ctx.$auth.fetchUser()
  }
})

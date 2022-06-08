import { defineNuxtMiddleware } from '@nuxtjs/composition-api'
export default defineNuxtMiddleware((ctx) => {

    if (process.client) {
        ctx.$auth.fetchUser()
    }

})

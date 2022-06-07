import { computed } from '@nuxtjs/composition-api'
export default function ({ $auth, app, redirect, store }) {

    const user = computed(() => store.state.auth.user)

    if (process.client) {
        $auth.fetchUser()
    }

    if (!user.value.username) {
        //return redirect('/user/update')
    }

    if (!user.value.tutorial && !app.router.history.current.path.includes('/tutorial')) {
        return redirect('/tutorial')
    }

}

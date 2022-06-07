import { computed } from '@nuxtjs/composition-api'
export default function ({ $auth, store }) {

    const user = computed(() => store.state.auth.user)

    if (process.client) {
        $auth.fetchUser()
    }

    if ($auth.loggedIn && !user.value.username) {
        //return redirect('/user/update')
    }

}

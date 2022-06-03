export default function ({ $auth, store, redirect }) {

    if (process.client) {
        $auth.fetchUser()
    }

    if (!store.state.username) {
        //return redirect('/user/update')
    }
}

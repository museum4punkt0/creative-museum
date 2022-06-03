export default function ({ $auth, store, redirect }) {

    $auth.fetchUser()

    if (!store.state.username) {
        //return redirect('/user/update')
    }
}

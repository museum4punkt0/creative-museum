export default (context, inject) => {
  inject('userName', (user) => {
    if (user) {
      if (user.deleted) {
        return $t('anonymous')
      } else {
        return `@${user.username}`
      }
    } else {
      return $t('deleted')
    }
  })
}
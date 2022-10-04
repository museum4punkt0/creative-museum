export default (context, inject) => {

  const i18n = context.i18n

  inject('userName', (user) => {
    if (user) {
      if (user.deleted) {
        return i18n.t('anonymous')
      } else {
        return `@${user.username}`
      }
    } else {
      return i18n.t('deleted')
    }
  })
}
export default (context, inject) => {

  const i18n = context.i18n

  inject('userName', (user) => {
    if (user) {
      if (user.deleted) {
        return user.lastBadge ? user.lastBadge.title : i18n.t('anonymous')
      }
      return `@${user.username}`
    }
    return i18n.t('deleted')
  })
}

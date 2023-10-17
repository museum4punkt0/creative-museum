import { useContext, useStore } from '@nuxtjs/composition-api'

export const cmsApi = () => {
  const { $api, $auth } = useContext()
  const store = useStore()

  async function fetchImprint() {
    return await $api.get(`cms_contents/imprint`)
  }

  async function fetchAbout() {
    return await $api.get(`cms_contents/about`)
  }

  async function fetchFaq() {
    return await $api.get(`cms_contents/faq`)
  }

  async function fetchSignLanguage() {
    return await $api.get(`cms_contents/signLanguage`)
  }

  async function fetchSimpleLanguage() {
    return await $api.get(`cms_contents/simpleLanguage`)
  }

  async function fetchAccessibility() {
    return await $api.get(`cms_contents/accessibility`)
  }

  return {
    fetchImprint,
    fetchAbout,
    fetchFaq,
    fetchSignLanguage,
    fetchSimpleLanguage,
    fetchAccessibility
  }
}

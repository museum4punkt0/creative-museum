<template>
  <div></div>
</template>
<script>
import {
  computed,
  defineComponent,
  useStore,
  useContext,
  ref
} from '@nuxtjs/composition-api'
import { userApi } from '@/api/user'

export default defineComponent({
  name: 'UserUpdate',
  components: {
    FileUpload: () => import('vue-upload-component'),
  },
  setup() {
    const store = useStore()
    const user = computed(() => store.state.auth.user)
    const { $config } = useContext()

    const title = ref('Stammgast')
    const description = ref(user.value.description)
    const firstName = ref(user.value.firstName)
    const lastName = ref(user.value.lastName)
    const email = ref(user.value.email)
    const username = ref(user.value.username)
    const files = ref([])
    const changed = ref(false)

    const { updateUser } = userApi()

    store.dispatch('hideAddButton')
    store.dispatch('setCurrentCampaign', null)

    if ('profilePicture' in user.value) {
      files.value = [
        $config.backendUrl + user.value.profilePicture.contentUrl,
      ]
    }

    function inputFile(newFile, oldFile, prevent) {
      if (newFile && (!oldFile || newFile.file !== oldFile.file)) {
        newFile.blob = ''
        const URL = window.URL || window.webkitURL
        if (URL && URL.createObjectURL) {
          newFile.blob = URL.createObjectURL(newFile.file)
        }
      }
      if (!newFile && oldFile) {
        files.value = []
      }
      changed.value = true
    }

    function inputFilter(newFile, oldFile, prevent) {
      if (newFile) {
        if (!/\.(gif|jpg|jpeg|png|webp)$/i.test(newFile.name)) {
          return prevent()
        }
      }
    }

    function save() {
      const updateData = {
        firstName: firstName.value,
        lastName: lastName.value,
        description: description.value,
        username: username.value,
      }

      if (changed.value && files.value.length > 0) {
        updateData.picture = files.value[0]
      }

      updateUser(updateData)
    }

    function remove() {
      console.log('deleting user')
    }

    return {
      files,
      user,
      description,
      firstName,
      lastName,
      email,
      username,
      title,
      inputFile,
      inputFilter,
      save,
      remove,
      changed,
      backendUrl: $config.backendUrl,
    }
  },
})
</script>

<template>
  <div>

    <client-only>

      <file-upload ref="upload"
                   v-model="files"
                   accept="image/png,image/gif,image/jpeg"
                   class="block"
                   @input-file="inputFile"
                   @input-filter="inputFilter"
      >
        <div class="box-shadow-inset" w:pt="2" w:pr="2" w:pb="10" w:pl="2" w:border="rounded-xl" w:text="left" w:flex="~ row">

          <img v-if="files.length" :src="typeof files[0] === 'string' ? files[0] : files[0].blob" w:max-h="1/3 lg:48" w:w="auto" w:border="rounded" w:align="self-center" />

          {{ $t('post.types.image.uploader.' + (files.length ? 'replace' : 'add')) }}
          <svg w:w="6" w:h="6" w:ml="2" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M12 22C17.5228 22 22 17.5228 22 12C22 6.47715 17.5228 2 12 2C6.47715 2 2 6.47715 2 12C2 17.5228 6.47715 22 12 22Z" stroke="#FFFFFF" stroke-miterlimit="10" stroke-linecap="round"/>
            <path d="M12 5.28571V18.7143" stroke="#FFFFFF" stroke-miterlimit="10" stroke-linecap="round"/>
            <path d="M18.7137 11.8514H5.28516" stroke="#FFFFFF" stroke-miterlimit="10" stroke-linecap="round"/>
          </svg>
        </div>
      </file-upload>
    </client-only>

    <h1>{{ firstName }} {{ lastName }}</h1>
    <p>{{ title }} @{{ user.username }}</p>
    <textarea v-model="description" type="text" class="input-text" w:flex="grow" w:pb="8" :maxlength="1000"></textarea>

    <h2>{{ $t('user.profile.self.edit.personalData') }}</h2>

    <label for="input_firstname">{{ $t('user.profile.self.edit.firstName') }}</label>
    <input id="input_firstname" type="text" v-model="firstName" class="input-text" />

    <label for="input_lastname">{{ $t('user.profile.self.edit.lastName') }}</label>
    <input id="input_lastname" type="text" v-model="lastName" class="input-text" />

    <label for="input_email">{{ $t('user.profile.self.edit.email') }}</label>
    <input id="input_email" type="text" v-model="email" class="input-text" />

    <label for="input_username">{{ $t('user.profile.self.edit.username') }}</label>
    <input id="input_username" type="text" v-model="username" class="input-text" />

    <button class="btn-primary" @click.prevent="save">{{ $t('user.profile.self.edit.save') }}</button>

    <h2>{{ $t('user.profile.self.edit.removal') }}</h2>

    <button class="btn-outline" @click.prevent="remove">{{ $t('user.profile.self.edit.deleteProfile') }}</button>


  </div>
</template>
<script>
import {computed, defineComponent, useStore, ref, useContext} from '@nuxtjs/composition-api'
import { userApi } from '@/api/user'

export default defineComponent({
  name: 'UserUpdate',
  components: {
    FileUpload: () => import('vue-upload-component')
  },
  setup() {
    const store = useStore()
    const user = computed(() => store.state.auth.user)

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

    if ('profilePicture' in user.value) {
      files.value = [
        'https://backend.creative-museum.ddev.site' + user.value.profilePicture.contentUrl
      ]
    }

    function inputFile(newFile, oldFile, prevent) {
      if (newFile && (!oldFile || newFile.file !== oldFile.file)) {
        newFile.blob = ''
        let URL = window.URL || window.webkitURL
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

      let updateData = {
        firstName: firstName.value,
        lastName: lastName.value,
        description: description.value,
        username: username.value
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
      changed
    }
  },
})
</script>

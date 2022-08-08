<template>
  <div w:flex="~ col 1" w:h="full" w:justify="between" w:pr="6" w:pb="6" w:pl="6">

    <client-only>
      <div w:flex="~ col" w:align="items-start">
        <img v-if="files.length" :src="typeof files[0] === 'string' ? files[0] : files[0].blob" w:w="32" w:mr="2" w:mb="2" w:border="rounded-full" w:align="self-start" />
        <file-upload ref="upload"
                     v-model="files"
                     accept="image/png,image/gif,image/jpeg"
                     class="block"
                     @input-file="inputFile"
                     @input-filter="inputFilter"
        >
          <div w:flex="~ row">
            <svg v-if="!files.length" w:w="6" w:h="6" w:mr="2" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="highlight-svg-stroke">
              <path d="M12 22C17.5228 22 22 17.5228 22 12C22 6.47715 17.5228 2 12 2C6.47715 2 2 6.47715 2 12C2 17.5228 6.47715 22 12 22Z" stroke-miterlimit="10" stroke-linecap="round"/>
              <path d="M12 5.28571V18.7143" stroke-miterlimit="10" stroke-linecap="round"/>
              <path d="M18.7137 11.8514H5.28516" stroke-miterlimit="10" stroke-linecap="round"/>
            </svg>
            <svg v-if="files.length" w:w="6" w:h="6" w:mr="2" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg" class="highlight-svg-stroke">
              <path d="M12.812 13.1936C13.4218 12.6459 13.9076 11.9743 14.2368 11.2238C14.5661 10.4732 14.7313 9.66098 14.7213 8.84143C14.7207 7.29236 14.1051 5.8069 13.0098 4.71154C11.9144 3.61618 10.429 3.00057 8.87988 3" stroke-miterlimit="10" stroke-linecap="round"/>
              <path d="M17.072 13.4783H13.3435C13.1161 13.4783 12.8981 13.388 12.7374 13.2272C12.5766 13.0665 12.4863 12.8485 12.4863 12.6211V8.89258" stroke-miterlimit="10" stroke-linecap="round"/>
              <path d="M5.26028 4.43994C4.65056 4.98903 4.1649 5.66178 3.83567 6.41336C3.50644 7.16493 3.34121 7.97805 3.351 8.79851C3.35156 10.3476 3.96718 11.833 5.06254 12.9284C6.1579 14.0238 7.64335 14.6394 9.19242 14.6399" stroke-miterlimit="10" stroke-linecap="round"/>
              <path d="M1 4.16113H4.72857C4.9559 4.16113 5.17392 4.25144 5.33466 4.41218C5.49541 4.57293 5.58571 4.79095 5.58571 5.01828V8.74685" stroke-miterlimit="10" stroke-linecap="round"/>
            </svg>
            <span class="highlight-text">
              {{ $t('post.types.image.uploader.' + (files.length ? 'replace' : 'add')) }}
            </span>
          </div>
        </file-upload>
      </div>
    </client-only>

    <div w:mb="12">
      <h1 w:text="2xl" w:mt="6">{{ firstName }} {{ lastName }}</h1>
      <p class="highlight-text" w:mb="4">{{ title }} @{{ user.username }}</p>
      <textarea v-model="description" type="text" class="input-text" :placeholder="$t('user.profile.self.edit.placeholder.description')" w:flex="grow" :maxlength="1000"></textarea>
    </div>

    <div w:mb="12">
      <h2 w:text="2xl">{{ $t('user.profile.self.edit.personalData') }}</h2>
      <div w:mt="4">
        <label for="input_firstname" w:pl="2" w:text="sm" w:mb="3" class="highlight-text">{{ $t('user.profile.self.edit.firstName') }}</label>
        <input id="input_firstname" type="text" v-model="firstName" class="input-text" />
      </div>
      <div w:mt="4">
        <label for="input_lastname" w:pl="2" w:text="sm" w:mb="3" class="highlight-text">{{ $t('user.profile.self.edit.lastName') }}</label>
        <input id="input_lastname" type="text" v-model="lastName" class="input-text" />
      </div>
      <div w:mt="4">
        <label for="input_email" w:pl="2" w:text="sm" w:mb="3" class="highlight-text">{{ $t('user.profile.self.edit.email') }}</label>
        <input id="input_email" type="text" v-model="email" class="input-text" />
      </div>
      <div w:mt="4">
        <label for="input_username" w:pl="2" w:text="sm" w:mb="3" class="highlight-text">{{ $t('user.profile.self.edit.username') }}</label>
        <input id="input_username" type="text" v-model="username" class="input-text" />
      </div>
    </div>

    <div w:mb="12">
      <h2 w:text="2xl">{{ $t('user.profile.self.edit.notifications') }}</h2>
      <p class="highlight-text" w:text="sm" w:mt="4" w:mb="3">Persönliche Benachrichtigungen</p>
      <div w:display="inline-block">
        <div class="toggle" w:flex="~ row" w:overflow="hidden">
          <div class="toggle__item">
            <input id="persNotifyOn" w:w="0" w:h="0" w:overflow="hidden" type="radio" value="0" name="persNotify" checked>
            <label for="persNotifyOn" w:px="3" w:display="inline-block" w:font="leading-loose" w:transition="background-color duration-300">Ja</label>
          </div>
          <div class="toggle__item">
            <input id="persNotifyOff" w:w="0" w:h="0" w:overflow="hidden" type="radio" value="1" name="persNotify">
            <label for="persNotifyOff" w:px="3" w:display="inline-block" w:font="leading-loose" w:transition="background-color duration-300">Nein</label>
          </div>
        </div>
      </div>

      <p class="highlight-text" w:text="sm" w:mt="4" w:mb="3">Creative Museum Benachrichtigungen</p>
      <div w:display="inline-block">
        <div class="toggle" w:flex="~ row" w:overflow="hidden">
          <div class="toggle__item">
            <input id="globalNotifyOn" w:w="0" w:h="0" w:overflow="hidden" type="radio" value="0" name="globalNotify">
            <label for="globalNotifyOn" w:px="3" w:display="inline-block" w:font="leading-loose" w:transition="background-color duration-300">Ja</label>
          </div>
          <div class="toggle__item">
            <input id="globalNotifyOff" w:w="0" w:h="0" w:overflow="hidden" type="radio" value="1" name="globalNotify" checked>
            <label for="globalNotifyOff" w:px="3" w:display="inline-block" w:font="leading-loose" w:transition="background-color duration-300">Nein</label>
          </div>
        </div>
      </div>
    </div>
    <button w:align="md:self-start" class="btn-primary" @click.prevent="save" w:mb="12">{{ $t('user.profile.self.edit.save') }}</button>

    <div w:flex="~ col">
      <h2 w:text="2xl">{{ $t('user.profile.self.edit.removal') }}</h2>
      <button w:align="md:self-start" class="btn-primary btn-outline" @click.prevent="remove">{{ $t('user.profile.self.edit.deleteProfile') }}</button>
    </div>


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

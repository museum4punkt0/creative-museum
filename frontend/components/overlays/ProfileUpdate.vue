<template>
  <UtilitiesModal v-if="showProfileUpdate" @closeModal="showProfileUpdate = false">
    <div v-show="!showDeleteUser" class="px-6 pb-6">
      <div class="page-header">
        <a class="back-btn" @click.prevent="showProfileUpdate = false">
        {{ $t('globalProfileUpdate.header') }}</a>
      </div>
      <client-only>
        <div class="flex flex-col items-start">
          <div class="w-32 h-32 overflow-hidden mr-2 mb-2 rounded-full self-start border border-$highlight">
            <img
              v-if="files.length"
              :src="typeof files[0] === 'string' ? files[0] : files[0].blob"
              class="object-center w-32 h-32"
            />
          </div>
          <file-upload
            ref="upload"
            v-model="files"
            accept="image/png,image/gif,image/jpeg"
            class="block"
            @input-file="inputFile"
            @input-filter="inputFilter"
          >
            <div class="flex flex-row">
              <svg
                v-if="!files.length"
                viewBox="0 0 24 24"
                fill="none"
                xmlns="http://www.w3.org/2000/svg"
                class="highlight-svg-stroke w-6 -h6 mr-2"
              >
                <path
                  d="M12 22C17.5228 22 22 17.5228 22 12C22 6.47715 17.5228 2 12 2C6.47715 2 2 6.47715 2 12C2 17.5228 6.47715 22 12 22Z"
                  stroke-miterlimit="10"
                  stroke-linecap="round"
                />
                <path
                  d="M12 5.28571V18.7143"
                  stroke-miterlimit="10"
                  stroke-linecap="round"
                />
                <path
                  d="M18.7137 11.8514H5.28516"
                  stroke-miterlimit="10"
                  stroke-linecap="round"
                />
              </svg>
              <svg
                v-if="files.length"
                viewBox="0 0 18 18"
                fill="none"
                xmlns="http://www.w3.org/2000/svg"
                class="highlight-svg-stroke w-6 h-6 mr-2"
              >
                <path
                  d="M12.812 13.1936C13.4218 12.6459 13.9076 11.9743 14.2368 11.2238C14.5661 10.4732 14.7313 9.66098 14.7213 8.84143C14.7207 7.29236 14.1051 5.8069 13.0098 4.71154C11.9144 3.61618 10.429 3.00057 8.87988 3"
                  stroke-miterlimit="10"
                  stroke-linecap="round"
                />
                <path
                  d="M17.072 13.4783H13.3435C13.1161 13.4783 12.8981 13.388 12.7374 13.2272C12.5766 13.0665 12.4863 12.8485 12.4863 12.6211V8.89258"
                  stroke-miterlimit="10"
                  stroke-linecap="round"
                />
                <path
                  d="M5.26028 4.43994C4.65056 4.98903 4.1649 5.66178 3.83567 6.41336C3.50644 7.16493 3.34121 7.97805 3.351 8.79851C3.35156 10.3476 3.96718 11.833 5.06254 12.9284C6.1579 14.0238 7.64335 14.6394 9.19242 14.6399"
                  stroke-miterlimit="10"
                  stroke-linecap="round"
                />
                <path
                  d="M1 4.16113H4.72857C4.9559 4.16113 5.17392 4.25144 5.33466 4.41218C5.49541 4.57293 5.58571 4.79095 5.58571 5.01828V8.74685"
                  stroke-miterlimit="10"
                  stroke-linecap="round"
                />
              </svg>
              <span class="highlight-text">
                {{
                  $t(
                    'post.types.image.uploader.' +
                      (files.length ? 'replace' : 'add')
                  )
                }}
              </span>
            </div>
          </file-upload>
        </div>
      </client-only>

      <div class="mb-12">
        <h1 class="text-2xl mt-6">{{ firstName }} {{ lastName }}</h1>
        <p class="highlight-text mb-4">{{ title }} @{{ user.username }}</p>
        <textarea
          v-model="description"
          type="text"
          class="input-text flex-grow"
          :placeholder="$t('user.profile.self.edit.placeholder.description')"
          :maxlength="1000"
        ></textarea>
      </div>

      <div class="mb-12">
        <h2 class="text-2xl">{{ $t('user.profile.self.edit.personalData') }}</h2>
        <div class="mt-4">
          <label
            for="input_firstname"
            class="highlight-text pl-2 text-sm mb-3"
            >{{ $t('user.profile.self.edit.firstName') }}</label
          >
          <input
            id="input_firstname"
            v-model="firstName"
            type="text"
            class="input-text"
          />
        </div>
        <div class="mt-4">
          <label
            for="input_lastname"
            class="highlight-text pl-2 text-sm mb-3"
            >{{ $t('user.profile.self.edit.lastName') }}</label
          >
          <input
            id="input_lastname"
            v-model="lastName"
            type="text"
            class="input-text"
          />
        </div>
        <div class="mt-4">
          <label
            for="input_email"
            class="highlight-text pl-2 text-sm mb-3"
            >{{ $t('user.profile.self.edit.email') }}</label
          >
          <input
            id="input_email"
            v-model="email"
            type="text"
            class="input-text"
          />
        </div>
        <div class="mt-4">
          <label
            for="input_username"
            class="highlight-text pl-2 text-sm mb-3"
            >{{ $t('user.profile.self.edit.username') }}</label
          >
          <input
            id="input_username"
            v-model="username"
            type="text"
            class="input-text"
          />
        </div>
      </div>

      <div class="mb-12">
        <h2 class="text-2xl">{{ $t('user.profile.self.edit.notifications') }}</h2>
        <p class="highlight-text text-sm mt-4 mb-3">
          Persönliche Benachrichtigungen
        </p>
        <div class="inline-block">
          <div class="toggle flex flex row overflow-hidden">
            <div class="toggle__item">
              <input
                id="persNotifyOn"
                type="radio"
                value="0"
                name="persNotify"
                checked
                class="w-0 h-0 overflow-hidden"
              />
              <label
                for="persNotifyOn"
                class="px-3 inline-block leading-loose transition transition-colors duration-300"
                >Ja</label
              >
            </div>
            <div class="toggle__item">
              <input
                id="persNotifyOff"
                type="radio"
                value="1"
                name="persNotify"
                class="w-0 h-0 overflow-hidden"
              />
              <label
                for="persNotifyOff"
                class="px-3 inline-block leading-loose transition transition-colors duration-300"
                >Nein</label
              >
            </div>
          </div>
        </div>

        <p class="highlight-text text-sm mt-3 mb-3">
          Creative Museum Benachrichtigungen
        </p>
        <div class="inline-block">
          <div class="toggle flex flex-row overflow-hidden">
            <div class="toggle__item">
              <input
                id="globalNotifyOn"
                type="radio"
                value="0"
                name="globalNotify"
                class="w-0 h-0 overflow-hidden"
              />
              <label
                for="globalNotifyOn"
                class="px-3 inline-block leading-loose transition transition-colors duration-300"
                >Ja</label
              >
            </div>
            <div class="toggle__item">
              <input
                id="globalNotifyOff"
                type="radio"
                value="1"
                name="globalNotify"
                checked
                class="w-0 h-0 overflow-hidden"
              />
              <label
                for="globalNotifyOff"
                class="px-3 inline-block leading-loose transition transition-colors duration-300"
                >Nein</label
              >
            </div>
          </div>
        </div>
      </div>
      <button
        class="btn-highlight mb-12 w-full md:w-auto md:min-w-xs"
        @click.prevent="save"
      >
        {{ $t('user.profile.self.edit.save') }}
      </button>

      <div class="mb-12">
        <h2 class="text-2xl">{{ $t('user.profile.self.edit.removal') }}</h2>
        <button
          class="btn-outline w-full md:w-auto md:min-w-xs"
          @click.prevent="showDeleteUser = true"
        >
          {{ $t('user.profile.self.edit.deleteProfile') }}
        </button>
      </div>
    </div>
    <div v-show="showDeleteUser" class="flex flex-col flex-1 h-full justify-between px-6 pb-6">
      <div>
        <div class="page-header">
          <a class="back-btn" @click.prevent="showDeleteUser = false">
            {{ $t('globalProfileUpdate.deleteUser.header') }}
          </a>
        </div>
        <div class="box-shadow-mobile p-6 md:p-0 mb-4">
          <p class="text-xl mb-4">{{ $t('globalProfileUpdate.deleteUser.subheader') }}</p>
          <div class="form-check flex flex-row mb-4">
            <div class="flex-grow-0">
              <div class="box-shadow-small p-1 rounded-full overflow-hidden inline-block mr-2">
                <input id="deleteUserModeDelete" class="form-check-input appearance-none rounded-full h-4 w-4 border-1 border-grey bg-grey checked:bg-$highlight checked:border-grey focus:outline-none transition duration-200 align-top bg-no-repeat bg-center bg-contain float-left cursor-pointer" type="radio" name="flexRadioDefault">
              </div>
            </div>
            <label class="form-check-label inline-block -t-1" for="deleteUserModeDelete">
              {{ $t('globalProfileUpdate.deleteUser.options.permanent')}}
            </label>
          </div>
          <div class="form-check flex flex-row">
            <div class="flex-grow-0">
              <div class="box-shadow-small p-1 rounded-full overflow-hidden inline-block mr-2">
                <input id="deleteUserModeAnonymize" class="form-check-input appearance-none rounded-full h-4 w-4 border-1 border-grey bg-grey checked:bg-$highlight checked:border-grey focus:outline-none transition duration-200 align-top bg-no-repeat bg-center bg-contain float-left cursor-pointer" type="radio" name="flexRadioDefault" checked>
              </div>
            </div>
            <label class="form-check-label inline-block -t-1" for="deleteUserModeAnonymize">
              {{ $t('globalProfileUpdate.deleteUser.options.anonymize')}}
            </label>
          </div>
        </div>
      </div>
      <div class="grid grid-cols-1 gap-4 md:(grid-cols-2)">
        <button
          class="btn-highlight w-full md:min-w-xs"
          @click.prevent="showDeleteUser = showProfileUpdate = false"
        >
          {{ $t('globalProfileUpdate.deleteUser.abort')}}
        </button>
        <button
          class="btn-outline w-full md:min-w-xs"
          @click.prevent="showDeleteUser = showProfileUpdate = false"
        >
          {{ $t('globalProfileUpdate.deleteUser.confirm')}}
        </button>
      </div>
    </div>
  </UtilitiesModal>
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
      const showDeleteUser = ref(false)

      const showProfileUpdate = computed({
        get() { return store.state.showProfileUpdate },
        set() {
          store.dispatch('hideProfileUpdate')
        }
      })

      const { updateUser, removeUser } = userApi()

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
          username: username.value
        }

        if (changed.value && files.value.length > 0) {
          updateData.picture = files.value[0]
        }

        updateUser(updateData)

        showProfileUpdate.value = false

      }

      function remove() {

      }

      return {
        showDeleteUser,
        showProfileUpdate,
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
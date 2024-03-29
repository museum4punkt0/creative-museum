<template>
  <div :style="styleAttr">
    <button
      class="flex flex-row items-center mb-2 award-item cursor-pointer text-left block w-full"
      @click.prevent="awardDetailOpen = true"
    >
      <template v-if="!linkOnly">
        <div class="w-20 h-24 overflow-hidden mr-3 flex-shrink-0">
          <AwardIcon v-if="award.picture" :image="award.picture" :title="award.title" class="h-22 w-auto" />
        </div>
        <div class="flex flex-col flex-grow">
          <p class="mb-1">{{ award.title }}</p>
          <p class="text-$highlight text-sm">

            <span v-if="created">
              {{
                $dayjs.duration($dayjs().diff($dayjs(created))).days() > 2
                  ? $dayjs(created).format( $t('dateFormat') )
                  : $dayjs(created).locale($i18n.locale).fromNow()
              }}
              <template v-if="$auth.loggedIn">
                {{
                  $auth.user.username === giver ? ` ${$t('awards.to')} ${winner}` : ` ${$t('awards.from')} ${giver}`
                }}
              </template>
              <template v-else>
                {{ $t('awards.to') }} {{ winner }}
              </template>
            </span>
            <span v-else>{{ award.price.toLocaleString() + ' ' + $t('points') }}</span>
          </p>
          <button
            v-if="available"
            class="btn-outline self-start mt-2 text-xs p-1"
            type="button"
          >
            {{ $t('awards.gift') }}
          </button>
        </div>
      </template>
      <template v-else>
        <span class="link-arrow mt-4">{{ $t('notifications.openAwardDetails') }}</span>
      </template>
    </button>
    <transition
      enter-active-class="duration-300 ease-out opacity-0"
      enter-to-class="opacity-100"
      leave-active-class="duration-200 ease-in"
      leave-class="opacity-100"
      leave-to-class="opacity-0"
    >
      <UtilitiesModal
        v-if="awardDetailOpen === true"
        :aria-label="$t('modal.awardDetail') + ' ' + award.title"
        @closeModal="awardDetailOpen = false"
      >
        <AwardDetail
          :award="award"
          :available="available"
          @closeAwardDetail="
            awardDetailOpen = false
            $emit('awardsChange')
          "
        />
      </UtilitiesModal>
    </transition>
  </div>
</template>
<script lang="ts">
import {
  defineComponent,
  useContext,
  ref,
  computed,
} from '@nuxtjs/composition-api'

export default defineComponent({
  props: {
    award: {
      type: Object,
      required: true,
    },
    available: {
      type: Boolean,
      default: false
    },
    giver: {
      type: String,
      default: null
    },
    winner: {
      type: String,
      default: null
    },
    created: {
      type: Date,
      default: null
    },
    linkOnly: {
      type: Boolean,
      default: false
    }
  },
  emits: ['awardsChange'],
  setup(props) {
    const context = useContext()
    const awardDetailOpen = ref(false)

    const styleAttr = computed(() => {
      return `--highlight: ${props.award.campaign.color}`
    })

    return {
      styleAttr,
      awardDetailOpen,
      backendURL: context.$config.backendURL,
    }
  },
})
</script>

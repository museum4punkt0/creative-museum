<template>
  <div class="flex flex-row items-center">
    <div class="h-6 w-6 text-center">
      <div v-if="isLoading" class="audio__play-loading">
        <span
          v-for="item in 8"
          :key="item"
          class="bg-$highlight"
        />
      </div>

      <div v-else class="cursor-pointer">
        <button
          v-show="!isPlaying"
          ref="playButton"
          class="transform translate-y-[-3px]"
          @click.stop="play"
        >
          <PlayIcon class="fill-white" />
        </button>

        <button
          v-show="isPlaying"
          ref="pauseButton"
          @click.stop="pause"
        >
          <PauseIcon class="fill-white" />
        </button>
      </div>
    </div>
    <div
      ref="audioProgressWrap"
      class="h-px flex-1 mx-3 bg-white/30 relative""
      @click.stop="handleClickProgressWrap"
    >
      <div
        ref="audioProgress"
        class="audio__progress"
      />
      <span ref="audioProgressPercent" class="h-px bg-white absolute top-0 left-0" />
      <div
        ref="audioProgressPoint"
        class="bg-$highlight w-2 h-2 rounded-full absolute left-0 top-0 transform -translate-x-1/2 -translate-y-1/2"
        @panstart="handleProgressPanstart"
        @panend="handleProgressPanend"
        @panmove="handleProgressPanmove"
      />
    </div>
    <div class="bg-white rounded-xl min-w-20 text-center py-1 whitespace-nowrap text-xs text-black">
      {{ currentTimeFormatted }} / {{ durationFormatted }}
    </div>
    <audio
      ref="audio"
      class="audio-player__audio"
      :src="audioList[currentPlayIndex]"
      v-bind="$attrs"
      @ended="onEnded"
      @timeupdate="onTimeUpdate"
      @loadedmetadata="onLoadedmetadata"
    />
  </div>
</template>

<script>
import Core from '@any-touch/core'
import Pan from '@any-touch/pan'
import PlayIcon from '@/assets/icons/play.svg?inline'
import PauseIcon from '@/assets/icons/pause.svg?inline'

export default {
  name: 'AudioPlayer',
  components: {
    PlayIcon,
    PauseIcon,
  },

  inheritAttrs: false,

  props: {
    audioList: {
      default: null,
      type: Array,
    },
    beforePlay: {
      default: null,
      type: Function,
    },
    progressInterval: {
      default: 100,
      type: Number,
    },
  },

  emits: [
    'pause',
    'timeupdate',
    'loadedmetadata',
    'ended',
    'progress-start',
    'progress-end',
    'progress-move',
    'progress-click',
    'playing',
    'play',
    'play-error',
  ],

  data() {
    return {
      isIOS: /iPhone|iPad|iPod/i.test(window.navigator.userAgent),
      isPlaying: false,
      isDragging: false,
      isShowErrorMessage: false,
      isLoading: false,
      timer: null,
      noticeMessage: '',
      duration: 0,
      currentPlayIndex: 0,
      currentTime: '',
      at: null,
    }
  },

  computed: {
    currentTimeFormatted() {
      return this.currentTime ? this.formatTime(this.currentTime) : '0:00'
    },

    durationFormatted() {
      return this.duration ? this.formatTime(this.duration) : '0:00'
    },
  },

  mounted() {
    this.at = new Core(this.$el, { preventDefault: false })
    this.at.use(Pan)
  },

  beforeUnmount() {
    this.at.destroy()
    this.pause()
  },

  methods: {
    handleShowErrorMessage(opts = {}) {
      this.noticeMessage = opts.message
      this.isShowErrorMessage = true

      window.setTimeout(() => {
        this.isShowErrorMessage = false
      }, opts.duration || 3000)
    },
    onLoadedmetadata(event) {
      if (this.$refs.audio.duration === Infinity) {
        this.$refs.audio.currentTime = 1e101
        this.$refs.audio.addEventListener('timeupdate', this.getDuration)
      } else {
        this.duration = this.$refs.audio.duration
      }
      this.$emit('loadedmetadata', event)
    },

    onTimeUpdate(event) {
      this.$emit('timeupdate', event)
    },

    getDuration() {
      this.$refs.audio.currentTime = 0
      this.duration = this.$refs.audio.duration
      this.$refs.audio.removeEventListener('timeupdate', this.getDuration)
    },

    formatTime(second) {
      let hour
      hour = Math.floor(second / 60)
      second = Math.ceil(second % 60)
      hour += ''
      second += ''
      second = second.length === 1 ? '0' + second : second
      return hour + ':' + second
    },

    onEnded(event) {
      window.setTimeout(() => {
        this.pause()
        this.$emit('ended', event)

        if (this.isLoop && this.isAutoPlayNext) {
          this.playNext()
        }
      }, 1000)
    },

    handleProgressPanstart(event) {
      if (this.disabledProgressDrag) return

      this.isDragging = true
      this.$emit('progress-start', event)
    },

    handleProgressPanend(event) {
      if (this.disabledProgressDrag) return

      this.$refs.audio.currentTime = this.currentTime
      this.play()
      this.isDragging = false
      this.$emit('progress-end', event)
    },

    handleProgressPanmove(event) {
      if (this.disabledProgressDrag) return

      const pageX = event.x
      const bcr = event.target.getBoundingClientRect()
      const targetLeft = parseInt(getComputedStyle(event.target).left)
      let offsetLeft = targetLeft + (pageX - bcr.left)

      offsetLeft = Math.min(
        offsetLeft,
        this.$refs.audioProgressWrap.offsetWidth
      )
      offsetLeft = Math.max(offsetLeft, 0)
      this.setProgressPosition(offsetLeft)
      this.$refs.audioProgress.style.width = offsetLeft + 'px'
      this.currentTime =
        (offsetLeft / this.$refs.audioProgressWrap.offsetWidth) * this.duration
      this.$emit('progress-move', event)
    },

    handleClickProgressWrap(event) {
      if (this.disabledProgressClick) return

      const target = event.target
      const offsetX = event.offsetX

      if (target === this.$refs.audioProgressPoint) {
        return
      }

      this.currentTime =
        (offsetX / this.$refs.audioProgressWrap.offsetWidth) * this.duration
      this.$refs.audio.currentTime = this.currentTime
      this.setProgressPosition(offsetX)
      this.$refs.audioProgress.style.width = offsetX + 'px'
      this.play()
      this.$emit('progress-click', event)
    },

    setProgressPosition(offsetLeft) {
      this.$refs.audioProgressPercent.style.width = offsetLeft - this.$refs.audioProgressPoint.offsetWidth / 2 + 'px'
      this.$refs.audioProgressPoint.style.left =
        offsetLeft - this.$refs.audioProgressPoint.offsetWidth / 2 + 'px'
    },

    playing() {
      if (this.isDragging) {
        return
      }

      const offsetLeft =
        (this.$refs.audio.currentTime / this.$refs.audio.duration) *
        this.$refs.audioProgressWrap.offsetWidth

      this.currentTime = this.$refs.audio.currentTime
      this.$refs.audioProgress.style.width = offsetLeft + 'px'
      this.setProgressPosition(offsetLeft)
      this.$emit('playing')
    },

    play() {
      this.isLoading = true
      const handlePlay = () => {
        this.$refs.audio
          .play()
          .then(() => {
            this.$nextTick(() => {
              this.clearTimer()
              this.timer = window.setInterval(
                this.playing,
                this.progressInterval
              )
              this.isPlaying = true
              this.isLoading = false
            })
            this.$emit('play')
          })
          .catch((data) => {
            this.handleShowErrorMessage({
              message: data.message,
            })

            if (data.code === 9) {
              if (this.isAutoPlayNext) {
                window.setTimeout(() => {
                  this.playNext()
                }, 1000)
              }
            }

            this.isLoading = false
            this.$emit('play-error', data)
          })
      }

      if (this.isIOS) {
        this.$refs.audio.play()
        this.$refs.audio.pause()
      }

      if (this.beforePlay) {
        this.beforePlay((state) => {
          if (state !== false) {
            handlePlay()
          }
        })
        return
      }

      handlePlay()
    },

    pause() {
      this.$refs.audio.pause()

      this.$nextTick(() => {
        this.$refs.playButton.focus()
        this.clearTimer()
        this.isPlaying = false
        this.$emit('pause')
      })
    },

    clearTimer() {
      window.clearInterval(this.timer)
      this.timer = null
    },
  },
}
</script>

<style>

.audio__play-loading {
  width: 42px;
  height: 42px;
  position: relative;
  margin: 0 16px;
}

.audio__play-loading span {
  display: inline-block;
  width: 8px;
  height: 8px;
  border-radius: 50%;
  position: absolute;
  animation: loading 1.04s ease infinite;
}

@keyframes loading {
  0% {
    opacity: 1;
  }
  100% {
    opacity: 0.2;
  }
}

.audio__play-loading span:nth-child(1) {
  left: 0;
  top: 50%;
  margin-top: -4px;
  animation-delay: 0.13s;
}
.audio__play-loading span:nth-child(2) {
  left: 7px;
  top: 7px;
  animation-delay: 0.26s;
}
.audio__play-loading span:nth-child(3) {
  left: 50%;
  top: 0;
  margin-left: -4px;
  animation-delay: 0.39s;
}
.audio__play-loading span:nth-child(4) {
  top: 7px;
  right: 7px;
  animation-delay: 0.52s;
}
.audio__play-loading span:nth-child(5) {
  right: 0;
  top: 50%;
  margin-top: -4px;
  animation-delay: 0.65s;
}
.audio__play-loading span:nth-child(6) {
  right: 7px;
  bottom: 7px;
  animation-delay: 0.78s;
}
.audio__play-loading span:nth-child(7) {
  bottom: 0;
  left: 50%;
  margin-left: -4px;
  animation-delay: 0.91s;
}
.audio__play-loading span:nth-child(8) {
  bottom: 7px;
  left: 7px;
  animation-delay: 1.04s;
}
</style>

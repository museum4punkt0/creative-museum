<template>
  <div class="audio-player">
    <div class="audio__btn-wrap">
      <div
        v-if="showPlaybackRate"
        class="audio__play-rate"
        :style="{
          color: themeColor,
        }"
      >
        <span @click.stop="isShowRates = !isShowRates">{{
          playbackRate.toFixed(1) + 'x'
        }}</span>
        <transition name="fade-rate">
          <div
            v-show="isShowRates"
            class="audio__play-rate__dropdown"
            :style="{
              backgroundColor: themeColor,
            }"
          >
            <div
              v-for="rate in playbackRates"
              :key="'pr_' + rate"
              @click.stop="handleSetPlaybackRate(rate)"
            >
              {{ rate.toFixed(1) + 'x' }}
            </div>
          </div>
        </transition>
      </div>

      <div
        v-if="showPrevButton"
        class="audio__play-prev"
        :class="{ disable: !isLoop && currentPlayIndex === 0 }"
        :style="{
          color: themeColor,
        }"
        @click.stop="playPrev"
      >
        <slot name="play-prev">
          <svg class="audio__play-icon" aria-hidden="true">
            <use xlink:href="#icon-play-prev" />
          </svg>
        </slot>
      </div>

      <div v-if="isLoading" class="audio__play-loading">
        <span
          v-for="item in 8"
          :key="item"
          :style="{
            backgroundColor: themeColor,
          }"
        />
      </div>

      <template v-else>
        <div
          v-if="!isPlaying && showPlayButton"
          class="audio__play-start"
          :style="{
            color: themeColor,
          }"
          @click.stop="play"
        >
          <slot name="play-start">
            <svg class="audio__play-icon" aria-hidden="true">
              <use xlink:href="#icon-play" />
            </svg>
          </slot>
        </div>

        <div
          v-else-if="showPlayButton"
          class="audio__play-pause"
          :style="{
            color: themeColor,
          }"
          @click.stop="pause"
        >
          <slot name="play-pause">
            <svg class="audio__play-icon" aria-hidden="true">
              <use xlink:href="#icon-play-pause" />
            </svg>
          </slot>
        </div>
      </template>

      <div
        v-if="showNextButton"
        class="audio__play-next"
        :class="{
          disable: !isLoop && currentPlayIndex === audioList.length - 1,
        }"
        :style="{
          color: themeColor,
        }"
        @click.stop="playNext"
      >
        <slot name="play-next">
          <svg class="audio__play-icon" aria-hidden="true">
            <use xlink:href="#icon-play-next" />
          </svg>
        </slot>
      </div>

      <div v-if="showVolumeButton" class="audio__play-volume-icon-wrap">
        <svg
          class="audio__play-icon"
          aria-hidden="true"
          :style="{
            color: themeColor,
          }"
          @click.stop="handleVolumeIconTouchstart"
        >
          <use
            :xlink:href="
              currentVolume ? `#icon-play-volume` : `#icon-play-volume-no`
            "
          />
        </svg>

        <transition name="fade-volume">
          <div
            v-show="isShowVolume"
            ref="playVolumeWrap"
            class="audio__play-volume-wrap"
            @click.stop="handleVolumePanmove"
            @panmove="handleVolumePanmove"
            @panend="handleVolumePanend"
          >
            <div
              ref="playVolume"
              class="audio__play-volume"
              :style="{
                height: currentVolume * 100 + '%',
                backgroundColor: themeColor,
              }"
            />
          </div>
        </transition>
      </div>

      <div v-show="isShowErrorMessage" class="audio__notice">
        {{ noticeMessage }}
      </div>
    </div>

    <div
      v-show="showProgressBar"
      ref="audioProgressWrap"
      class="audio__progress-wrap"
      @click.stop="handleClickProgressWrap"
    >
      <div
        ref="audioProgress"
        class="audio__progress"
        :style="{
          backgroundColor: themeColor,
        }"
      />
      <div
        ref="audioProgressPoint"
        class="audio__progress-point"
        :style="{
          backgroundColor: themeColor,
          boxShadow: `0 0 10px 0 ${themeColor}`,
        }"
        @panstart="handleProgressPanstart"
        @panend="handleProgressPanend"
        @panmove="handleProgressPanmove"
      />
    </div>

    <div v-show="showProgressBar" class="audio__time-wrap">
      <div class="audio__current-time">
        {{ currentTimeFormatted }}
      </div>
      <div class="audio__duration">
        {{ durationFormatted }}
      </div>
    </div>

    <audio
      ref="audio"
      class="audio-player__audio"
      :src="audioList[currentPlayIndex]"
      v-bind="$attrs"
      @ended="onEnded"
      @timeupdate="onTimeUpdate"
      @loadedmetadata="onLoadedmetadata"
    ></audio>
  </div>
</template>

<script>
import Core from '@any-touch/core'
import Pan from '@any-touch/pan'

export default {
  name: 'AudioPlayer',

  inheritAttrs: false,

  props: {
    audioList: {
      default: null,
      type: Array,
    },

    showPlayButton: {
      default: true,
      type: Boolean,
    },

    showPrevButton: {
      default: true,
      type: Boolean,
    },

    showNextButton: {
      default: true,
      type: Boolean,
    },

    showVolumeButton: {
      default: true,
      type: Boolean,
    },

    showProgressBar: {
      default: true,
      type: Boolean,
    },

    beforePlay: {
      default: null,
      type: Function,
    },

    beforePrev: {
      default: null,
      type: Function,
    },

    beforeNext: {
      default: null,
      type: Function,
    },

    isLoop: {
      type: Boolean,
      default: true,
    },

    isAutoPlayNext: {
      type: Boolean,
      default: true,
    },

    progressInterval: {
      default: 1000,
      type: Number,
    },

    showPlaybackRate: {
      type: Boolean,
      default: true,
    },

    playbackRates: {
      type: Array,
      default: () => [0.5, 1, 1.5, 2],
    },

    themeColor: {
      type: String,
      default: '#ff2929',
    },

    disabledProgressDrag: {
      type: Boolean,
      default: false,
    },

    disabledProgressClick: {
      type: Boolean,
      default: false,
    },
  },

  emits: [
    'pause',
    'play-prev',
    'play-next',
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
      isDraggingVolume: false,
      isShowErrorMessage: false,
      isLoading: false,
      isShowVolume: false,
      isShowRates: false,
      timer: null,
      noticeMessage: '',
      duration: '',
      currentPlayIndex: 0,
      currentTime: '',
      currentVolume: 1,
      playbackRate: 1,
      at: null,
    }
  },

  computed: {
    currentTimeFormatted() {
      return this.currentTime ? this.formatTime(this.currentTime) : '00:00'
    },

    durationFormatted() {
      return this.duration ? this.formatTime(this.duration) : '00:00'
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
    handleVolumeIconTouchstart() {
      this.isShowVolume = !this.isShowVolume
    },

    handleVolumePanmove(event) {
      const playVolumeWrapRect =
        this.$refs.playVolumeWrap.getBoundingClientRect()
      const pageY = event.y
      let offsetTop = 0
      let volume

      offsetTop = Math.round(playVolumeWrapRect.bottom - pageY)
      volume = offsetTop / this.$refs.playVolumeWrap.offsetHeight
      volume = Math.min(volume, 1)
      volume = Math.max(volume, 0)
      this.$refs.audio.volume = volume
      this.currentVolume = volume
    },
    handleVolumePanend() {
      this.isShowVolume = false
    },
    handleSetPlaybackRate(rate) {
      this.playbackRate = +rate
      this.$refs.audio.playbackRate = +rate
      this.isShowRates = false
    },
    handleShowErrorMessage(opts = {}) {
      this.noticeMessage = opts.message
      this.isShowErrorMessage = true

      window.setTimeout(() => {
        this.isShowErrorMessage = false
      }, opts.duration || 3000)
    },
    onLoadedmetadata(event) {
      this.duration = this.$refs.audio.duration
      this.$emit('loadedmetadata', event)
    },

    onTimeUpdate(event) {
      this.$emit('timeupdate', event)
    },

    formatTime(second) {
      let hour
      hour = Math.floor(second / 60)
      second = Math.ceil(second % 60)
      hour += ''
      second += ''
      hour = hour.length === 1 ? '0' + hour : hour
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
      this.setPointPosition(offsetLeft)
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
      this.setPointPosition(offsetX)
      this.$refs.audioProgress.style.width = offsetX + 'px'
      this.play()
      this.$emit('progress-click', event)
    },

    setPointPosition(offsetLeft) {
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
      this.setPointPosition(offsetLeft)
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
        console.log(
          '为了解决 iOS 设备接口异步请求后出现无法播放问题，请无视 The play() request was interrupted by a call to pause() 错误'
        )
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
        this.clearTimer()
        this.isPlaying = false
        this.$emit('pause')
      })
    },

    playPrev() {
      if (this.currentPlayIndex <= 0 && !this.isLoop) {
        return
      }

      this.clearTimer()

      const handlePrev = () => {
        if (this.currentPlayIndex <= 0 && this.isLoop) {
          this.currentPlayIndex = this.audioList.length - 1
        } else {
          this.currentPlayIndex--
        }

        this.$nextTick(() => {
          this.play()
          this.$emit('play-prev')
        })
      }

      if (this.beforePrev) {
        this.beforePrev((state) => {
          if (state !== false) {
            handlePrev()
          }
        })
        return
      }
      handlePrev()
    },

    clearTimer() {
      window.clearInterval(this.timer)
      this.timer = null
    },

    playNext() {
      if (this.currentPlayIndex + 1 >= this.audioList.length && !this.isLoop) {
        return
      }

      this.clearTimer()

      const handleNext = () => {
        if (this.currentPlayIndex + 1 >= this.audioList.length && this.isLoop) {
          this.currentPlayIndex = 0
        } else {
          this.currentPlayIndex++
        }

        this.$nextTick(() => {
          this.play()
          this.$emit('play-next')
        })
      }

      if (this.beforeNext) {
        this.beforeNext((state) => {
          if (state !== false) {
            handleNext()
          }
        })
        return
      }

      handleNext()
    },
  },
}
</script>

<style>
@keyframes fadeVolume {
  from {
    height: 0;
  }
  to {
    height: 50px;
  }
}

@keyframes fadeRate {
  from {
    max-height: 0;
  }
  to {
    max-height: 120px;
  }
}

.fade-volume-enter-active {
  animation: fadeVolume 0.3s;
}

.fade-volume-leave-active {
  animation: fadeVolume 0.3s reverse;
}

.fade-rate-enter-active {
  animation: fadeRate 0.3s;
}

.fade-rate-leave-active {
  animation: fadeRate 0.3s reverse;
}

.audio-player .audio__btn-wrap {
  position: relative;
  display: flex;
  align-items: center;
  justify-content: center;
}

.audio-player .audio__play-icon {
  width: 100%;
  height: 100%;
  fill: currentColor;
  overflow: hidden;
}

.audio-player .audio__play-volume-icon-wrap {
  position: relative;
  width: 21px;
  height: 21px;
  cursor: pointer;
  touch-action: none;
  user-select: none;
  -webkit-user-drag: none;
  margin-left: 16px;
}

.audio-player .audio__play-volume-icon-wrap .audio__play-volume-wrap {
  position: absolute;
  width: 21px;
  height: 50px;
  bottom: 21px;
  left: 0;
  background-color: #ddd;
  border-radius: 10px;
}

.audio-player
  .audio__play-volume-icon-wrap
  .audio__play-volume-wrap
  .audio__play-volume {
  position: absolute;
  right: 0;
  bottom: 0;
  left: 0;
  border-radius: 10px;
}

.audio-player .audio__play-rate {
  position: relative;
  height: 21px;
  line-height: 21px;
  cursor: pointer;
  touch-action: none;
  user-select: none;
  -webkit-user-drag: none;
  font-size: 16px;
  margin-right: 16px;
}

.audio-player .audio__play-rate__dropdown {
  position: absolute;
  bottom: 100%;
  left: 50%;
  transform: translateX(-50%);
  padding: 2px;
  color: #fff;
  border-radius: 15px;
  font-size: 12px;
  overflow: hidden;
}

.audio-player .audio__play-prev {
  cursor: pointer;
  touch-action: none;
  user-select: none;
  -webkit-user-drag: none;
}

.audio-player .audio__play-prev svg {
  display: block;
  width: 21px;
  height: 33px;
}

.audio-player .audio__play-prev.disable {
  opacity: 0.5;
}

.audio-player .audio__play-start {
  margin: 0 16px;
  cursor: pointer;
  touch-action: none;
  user-select: none;
  -webkit-user-drag: none;
}

.audio-player .audio__play-start svg {
  display: block;
  width: 42px;
  height: 42px;
}

.audio-player .audio__play-pause {
  margin: 0 16px;
  cursor: pointer;
  touch-action: none;
  user-select: none;
  -webkit-user-drag: none;
}

.audio-player .audio__play-pause svg {
  display: block;
  width: 42px;
  height: 42px;
}

.audio-player .audio__play-next {
  cursor: pointer;
  touch-action: none;
  user-select: none;
  -webkit-user-drag: none;
}

.audio-player .audio__play-next svg {
  display: block;
  width: 21px;
  height: 33px;
}

.audio-player .audio__play-next.disable {
  opacity: 0.5;
}

.audio__notice {
  position: absolute;
  bottom: -15px;
  color: rgb(189, 178, 178);
  border-radius: 4px;
  font-size: 12px;
}

.audio-player .audio__progress-wrap {
  position: relative;
  background: #ddd;
  height: 4px;
  border-radius: 3px;
  margin-top: 20px;
  cursor: pointer;
  touch-action: none;
  user-select: none;
  -webkit-user-drag: none;
}

.audio-player .audio__progress {
  position: absolute;
  left: 0;
  top: 0;
  bottom: 0;
  width: 0;
  border-radius: 3px;
}

.audio-player .audio__progress-point {
  position: absolute;
  left: -8px;
  top: 50%;
  width: 16px;
  height: 16px;
  border-radius: 50%;
  margin-top: -8px;
}

.audio-player .audio__progress-point:after {
  content: '';
  position: absolute;
  top: 50%;
  left: 50%;
  width: 8px;
  height: 8px;
  margin: -4px 0 0 -4px;
  background: #fff;
  border-radius: 50%;
}

.audio-player .audio__time-wrap {
  display: flex;
  flex-direction: row;
  justify-content: space-between;
  margin-top: 7px;
}

.audio-player .audio__current-time {
  font-size: 10px;
  color: #888;
}

.audio-player .audio__duration {
  font-size: 10px;
  color: #888;
}

.audio-player .audio-player__audio {
  display: block;
  margin: 0 auto;
}

@media (any-hover: hover) {
  .audio-player .audio__play-rate:hover > span {
    opacity: 0.7;
  }

  .audio-player .audio__play-rate__dropdown > div:hover,
  .audio__play-icon:hover {
    opacity: 0.7;
  }
}

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

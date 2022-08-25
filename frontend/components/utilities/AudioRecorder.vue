<template>
  <div class="flex items-center">
    <div>
      <div
        v-if="!audioFile && !recordAudioState"
        title="Start recording"
        class="rounded-full h-8 w-8 text-center py-1"
        :class="
          permissionStatus === 'denied' || audioIsPlaying
            ? 'bg-grey'
            : 'cursor-pointer'
        "
        @click="permissionStatus === 'denied' || audioIsPlaying === false ? recordAudio() : false"
      >
        <MicrophoneIcon class="h-6" />
      </div>
      <div
        v-if="recordAudioState"
        title="Stop recording"
        class="rounded-full bg-red recorder-stop h-8 w-8 text-center py-1 cursor-pointer"
        @click="stopRecordAudio"
      >
        <MicrophoneIcon class="h-6" />
      </div>
    </div>
    <div v-show="audioFile && !recordAudioState" ref="audio" class="flex flex-row items-center w-full">
      <UtilitiesAudioPlayer :audio-list="[audioFile]" class="flex-grow flex-1" />
    </div>
    <div v-show="!audioFile" class="flex flex-row flex-1 flex-grow items-center">
      <div ref="progress" class="h-px flex-1 mx-3 bg-white/30 relative">
        <span ref="progressPercent" class="h-px bg-white absolute top-0 left-0" />
        <span ref="progressDot" class="bg-$highlight w-2 h-2 rounded-full absolute left-0 top-0 transform -translate-x-1/2 -translate-y-1/2" />
      </div>
      <span ref="audioTimer" class="block py-1 self-end min-w-20 ml-2 text-center bg-white rounded-xl text-xs text-black whitespace-nowrap">0:00 / 1:00</span>
    </div>
    <div v-show="audioFile && !recordAudioState">
      <button
        title="Delete Audio"
        :disabled="audioIsPlaying"
        class="bg-red-500 rounded-full cursor-pointer w-6 ml-2"
        @click="deleteAudioFile"
      >
        x
      </button>
    </div>
  </div>
</template>

<script>
import MicrophoneIcon from '@/assets/icons/microphone.svg?inline'
export default {
  name: 'AudioRecorder',
  components: {
    MicrophoneIcon
  },
  props: {
    timer: {
      type: Boolean,
      default: true,
    },
    timerColor: {
      type: String,
      default: '#000',
    },
    timerFontSize: {
      type: Number,
      default: 1,
    },
    timerBackground: {
      type: String,
      default: '#ccc',
    },
    maxDuration: {
      type: Number,
      default: 60000
    }
  },
  data() {
    return {
      permissionStatus: null,
      recorder: null,
      audioIsPlaying: false,
      audioFile: null,
      audioType: 'audio/x-wav',
      audioFileName: 'audio.wav',
      uploadedAudioFile: null,
      recordAudioState: false,
      initialTime: Date.now(),
    };
  },
  watch: {
    uploadedAudioFile(value) {
      this.$emit('audioFile', value);
    },
  },
  mounted() {
    this.checkPermission();
  },
  methods: {
    async checkPermission() {
      const status = await navigator.permissions.query(
        { name: 'microphone' },
      );
      this.permissionStatus = status.state;
    },
    clearAudio() {
      const TIMER = this.$refs.audioTimer;
      this.initialTime = Date.now();
      TIMER.innerText = '';
    },
    checkAudioTime() {
      const TIMER = this.$refs.audioTimer;
      const timeDifference = Date.now() - this.initialTime;
      const formatted = this.convertAudioTime(timeDifference);

      const progressPercent = this.$refs.progressPercent
      const progressDot = this.$refs.progressDot

      progressPercent.style.width = timeDifference / 1000 + '%'
      progressDot.style.left = timeDifference / 1000 + '%'

      TIMER.innerHTML = `${formatted} / 1:00`;
      if (timeDifference > this.maxDuration) {
        this.stopRecordAudio()
      }
    },
    convertAudioTime(miliseconds) {
      const totalSeconds = Math.floor(miliseconds / 1000);
      const minutes = Math.floor(totalSeconds / 60);
      const seconds = totalSeconds - minutes * 60;
      const sec = seconds < 10 ? `0${seconds}` : seconds;
      return `${minutes}:${sec}`;
    },
    recordAudio() {
      this.clearAudio();
      try {
        const device = navigator.mediaDevices.getUserMedia({ audio: true });
        const items = [];
        device.then((stream) => {
          this.recorder = new MediaRecorder(stream);
          this.recorder.ondataavailable = (e) => {
            items.push(e.data);
            if (this.recorder.state === 'inactive') {
              const blob = new Blob(items, { type: this.audioType });
              this.audioFile = URL.createObjectURL(blob);
              this.uploadedAudioFile = blob;
            }
          };
          this.recordAudioState = true;
          this.audioInterval = setInterval(this.checkAudioTime, 500);
          this.recorder.start();
        }).catch((err) => {
          // eslint-disable-next-line no-alert
          alert(err);
          // eslint-disable-next-line no-console
          console.log(err);
        });
      } catch (err) {
        // eslint-disable-next-line no-alert
        alert(`Audio error:  ${err}`);
        // eslint-disable-next-line no-console
        console.error('Audio error: ', err);
      }
    },
    stopRecordAudio() {
      clearInterval(this.audioInterval);
      this.recorder.stop();
      this.recordAudioState = false;
    },
    deleteAudioFile() {
      if (this.recordAudioState === false) {
        this.audioFile = null;
        this.uploadedAudioFile = null;
      }
    },
  },
};
</script>

<style lang="scss" scoped>

.ar-icon {
  width: 40px;
  height: 40px;
  border-radius: 50%;
  padding: 5px;
}

audio {
  max-height: 100%;
  max-width: 100%;
  margin: auto;
  object-fit: contain;
}

.audio {
  margin: 0 5px;
}

.recorder-stop {
  animation: pulse 1500ms infinite;
}

@keyframes pulse {
  0% {
    @apply text-$highlight;
  }

  70% {
    @apply text-white;
  }

  100% {
    @apply text-$highlight;
  }
}

.deleteAudio {
  background-color: darkred;
  color: #fff;
  padding: 5px;
  border-radius: 50px;
  width: 30px;
  height: 30px;
  border: none;
  font-weight: bold;
}

.deleteAudio:disabled {
  background-color: palevioletred;
}

.pointer {
  cursor: pointer;
}
</style>
<template>
  <div class="campaign__wrapper">
    <div
      class="campaign__stack" w:p="x-5 t-10 md:t-5 md:x-0" w:h="2xl lg:4xl"
    >
      <div
        v-for="campaign in stack"
        ref="card"
        :key="campaign._id"
        class="vue-card-stack__card"
        w:select="none"
        w:pos="absolute"
        w:transform="origin-center"
        :w:z-index="campaign.zIndex"
        :style="{
          top: `${campaign.yPos}px`,
          width: `${campaign.width}px`,
          zIndex: campaign.zIndex,
          top: `${campaign.yPos}px`,
          width: `${campaign.width}px`,
          cursor: 'grab',
          transition: `transform ${
            isDragging ? 0 : speed
          }s ease,   ${speed}s ease`,
          transform: `
            rotate(${campaign.rotate}deg)
            translate(${campaign.xPos}px, 0)
          `,
        }"
      >
        <CampaignItem :campaign="campaign" />
      </div>
    </div>
  </div>
</template>
<script>
import { debounce } from '@/utilities/debounce'

export default {
  name: 'CampaignStack',
  props: {
    campaigns: {
      type: Array,
      default: () => [],
    },
    stackWidth: {
      type: [Number, String],
      default: () => '100%',
    },
    sensitivity: {
      type: Number,
      default: () => 0.05,
    },
    maxVisibleCards: {
      type: Number,
      default: () => 6,
    },
    speed: {
      type: Number,
      default: () => 0.2,
    },
    paddingHorizontal: {
      type: Number,
      default: () => 20,
    },
    paddingVertical: {
      type: Number,
      default: () => 20,
    },
  },
  data() {
    return {
      stack: [],
      width: 0,
      activeCardIndex: 1,
      isDragging: false,
      dragStartX: 50,
      dragStartY: 0,
      isDraggingPrevious: false,
      isMobile: false,
      cardWidth: 650,
      mobileYOffset: 70,
      initialized: false
    }
  },
  computed: {
    _stackWidth() {
      if (!this.stackWidth) {
        return this.cardWidth + this.paddingHorizontal * 2
      } else if (typeof this.stackWidth === "number") {
        return this.stackWidth
      }

      return this.width || this.$el.clientWidth
    },
    _maxVisibleCampaigns() {
      return this.campaigns.length > this.maxVisibleCards
        ? this.maxVisibleCards
        : this.campaigns.length - 1
    },
    elementXPosOffset() {
      return this.$el.getBoundingClientRect().x
    },
    elementYPosOffset() {
      return 20
    },
    isTouch() {
      return "ontouchstart" in window
    },
    dragEvent() {
      return this.isTouch ? "touchmove" : "mousemove"
    },
    touchStartEvent() {
      return this.isTouch ? "touchstart" : "mousedown"
    },
    touchEndEvent() {
      return this.isTouch ? "touchend" : "mouseup"
    },
    stackRestPoints() {
      return this.campaigns.map((item, index) => {
        const xOffset = document.getElementById('pageLogo').getBoundingClientRect().left + (((this.cardWidth / 2) - 100) * (index
         - 1))

        if (!index) {
          if (this.isMobile) {
            return {
              x: this.cardWidth * -1,
              y: this.$refs.card[0].clientHeight * -1 + 20
            }
          } else {
            return {
              x: (this.cardWidth - 100) * -1,
              y: 0
            }
          }
        } else if (index === 1) {
          if (this.isMobile) {
            return {
              x: document.getElementById('pageLogo').getBoundingClientRect().left + this.paddingHorizontal,
              y: this.mobileYOffset
            }
          } else {
            return {
              x: document.getElementById('pageLogo').getBoundingClientRect().left + this.paddingHorizontal,
              y: 0
            }
          }
        } else {
          // eslint-disable-next-line no-lonely-if
          if (this.isMobile) {
            return {
              x: 0,
              y: (-15 * index) + this.mobileYOffset
            }
          } else {
            return {
              x: xOffset,
              y: 0
            }
          }
        }
      })
    },
    cardDefaults() {
      return this.campaigns.map((campaign, index) => {
        const xPos = this.stackRestPoints[index].x
        const yPos = this.stackRestPoints[index].y
        let isMobile = false
        if (document.getElementById('globalHeader').clientWidth < 768) {
          isMobile = true
        }

        return {
          xPos: !isMobile ? index < this._maxVisibleCampaigns ? xPos : document.getElementById('pageLogo').getBoundingClientRect().left + (this.cardWidth * (index - 1)) : 0,
          yPos: isMobile ? index < this._maxVisibleCampaigns ? index === 0 ? 10 : this.mobileYOffset + (10 * index) * -1 : yPos - this.yPosOffset + this.mobileYOffset : 50,
          rotate: index !== 1 ? Math.floor(Math.random() * ( 3 - 1 + 1 ) -  1) * (Math.round(Math.random()) ? 1 : -1): 0,
          width: isMobile ? window.innerWidth - this.paddingHorizontal * 2 : this.cardWidth,
          zIndex: index !== 0 ? this.campaigns.length + index * -1 : this.isDraggingPrevious ? isMobile ? 0 : this._maxVisibleCampaigns : 0,
          isDragging: this.isDragging
        }
      })
    },
    xPosOffset() {
      return (
        (this._stackWidth - this.paddingHorizontal * 2 - this.cardWidth) /
        (this._maxVisibleCampaigns - 2)
      )
    },
    yPosOffset() {
      return 20
    },
    originalActiveCardIndex() {
      if (this.stack[this.activeCardIndex]) {
        return this.stack[this.activeCardIndex]._index
      }
      return 0
    },
  },
  watch: {
    _maxVisibleCards: {
      handler() {
        this.rebuild()
      },
    },
  },
  mounted() {
    this.init()
    window.addEventListener("resize", this.handleResize)
    this.$el.addEventListener(this.touchStartEvent, this.onTouchStart)
    document.addEventListener(this.touchEndEvent, this.onTouchEnd)
  },
  methods: {
    init() {
      this.stack = this.campaigns

      this.stack.unshift(this.stack.pop())

      this.stack = this.stack.map((campaign, index) => {
        return {
          _id: new Date().getTime() + index,
          _index: index,
          ...campaign,
          ...this.cardDefaults[index],
        }
      })
      this.$nextTick(() => {
        if (process.client) {
          if (window.innerWidth < 768) {
            this.isMobile = true
            this.cardWidth = window.innerWidth - this.paddingHorizontal
          }
        }
      })
    },
    rebuild() {
      this.$nextTick(() => {
        this.stack = this.stack.map((card, index) => {
          if (index === this.activeCardIndex) {
            document.documentElement.style.setProperty('--highlight', card.color)
          }
          return {
            ...card,
            ...this.cardDefaults[index],
          }
        })
      })
    },
    handleResize: debounce(function() {
      this.width = this.$el.clientWidth
      this.rebuild()
    }, 250),
    onNext() {
      const cardToMoveToBottomOfStack = this.stack.shift()
      this.stack.push(cardToMoveToBottomOfStack)
      this.rebuild()
    },
    onPrevious() {
      const cardToMoveToTopOfStack = this.stack.pop()
      this.stack.unshift(cardToMoveToTopOfStack)
      this.rebuild()
    },
    updateStack() {
      const activeCard = this.stack[1]
      const activeCardRestPoint = this.stackRestPoints[this.activeCardIndex]
      const distanceTravelledX = activeCard.xPos - activeCardRestPoint.x
      const minDistanceToTravel =
        (this.cardWidth + this.paddingHorizontal) / (1 / this.sensitivity)

      this.$emit("move", 0)
      if (this.isDraggingPrevious) {
        if (distanceTravelledX > minDistanceToTravel) {
          this.onPrevious()
        } else {
          this.rebuild()
        }
      } else if (distanceTravelledX * -1 > minDistanceToTravel) {
        this.onNext()
      }
    },
    moveStack(dragXPos) {
      const activeCardOffsetX = dragXPos - this.dragStartX
      const activeCardOffsetY = 0

      this.$emit(
        "move",
        activeCardOffsetX / (this.cardWidth + this.paddingHorizontal)
      )

      this.stack = this.stack.map((campaign, index) => {
        const isActiveCard = index === this.activeCardIndex
        let yPos
        const xPos = isActiveCard
          ? this.cardDefaults[index].xPos + activeCardOffsetX
          : this.cardDefaults[index].xPos +
            (this.xPosOffset / (this.cardWidth + this.paddingHorizontal)) *
              activeCardOffsetX
        if (this.isMobile) {
          yPos = isActiveCard
            ? this.cardDefaults[index].yPos + activeCardOffsetY
            : this.cardDefaults[index].yPos +
              (this.yPosOffset / (this.cardWidth + this.paddingVertical)) *
                activeCardOffsetY
        } else {
          yPos = campaign.yPos
        }
        const rotate = isActiveCard
          ? '0'
          : Math.floor(Math.random() * ( 3 - 1 + 1 ) -  1) * (Math.round(Math.random()) ? 1 : -1)

        return {
          ...campaign,
          ...this.cardDefaults[index],
          xPos,
          yPos,
          rotate,
          opacity:
            index === 0 && !this.isDraggingPrevious
              ? 1
              : this.cardDefaults[index].opacity,
        }
      })
    },
    getDragXPos(e) {
      return this.isTouch ? e.touches[0].clientX : e.clientX
    },
    getDragYPos(e) {
      return this.isTouch ? e.touches[0].clientY : e.clientY
    },
    onTouchStart(e) {
      this.isDragging = true
      this.dragStartX = this.getDragXPos(e) - this.elementXPosOffset
      this.dragStartY = this.getDragYPos(e) - this.elementYPosOffset

      document.addEventListener(this.dragEvent, this.onDrag)
    },
    onTouchEnd() {
      this.isDragging = false
      this.dragStartX = 0
      this.dragStartY = 0
      document.removeEventListener(this.dragEvent, this.onDrag)
      this.updateStack()
    },
    onDrag(e) {
      const dragXPos = this.getDragXPos(e) - this.elementXPosOffset

      this.isDraggingPrevious = dragXPos > this.dragStartX
      this.moveStack(dragXPos)
    },
  },
}
</script>
<style scoped>
.campaign__wrapper {
  position: relative;
}

.campaign__stack {
  position: relative;
  overflow: hidden;
}

</style>
<template>
  <div class="relative">
    <div
      class="relative overflow-hidden px-5 pt-10 md:pt-5 md:px-0 h-2xl lg:h-4xl"
    >
      <div
        v-for="campaign in stack"
        ref="card"
        :key="campaign._id"
        :data-href="localePath('/campaigns/' + campaign.id)"
        class="vue-card-stack__card select-none absolute transform origin-center"
        :style="{
          top: `${campaign.yPos}px`,
          width: `${campaign.width}px`,
          zIndex: campaign.zIndex,
          width: `${campaign.width}px`,
          cursor: 'grab',
          transition: `transform ${
            isDragging ? 0 : speed
          }s ease,   ${speed}s ease`,
          transform: `
            rotate(${campaign.rotate}deg)
            translate(${campaign.xPos}px, 0)
          `,
          opacity: campaign.opacity,
        }"
        @click="gotoStackItem(campaign.id, localePath('/campaigns/' + campaign.id))"
      >
        <CampaignStackItem :campaign="campaign" />
      </div>
    </div>
  </div>
</template>
<script>
import { TinyColor, readability } from '@ctrl/tinycolor'
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
      activeCardIndex: 0,
      isDragging: false,
      dragStartX: 50,
      dragStartY: 0,
      isDraggingPrevious: false,
      isMobile: false,
      cardWidth: 650,
      mobileYOffset: 70,
      initialized: false,
      touchElement: '',
    }
  },
  computed: {
    _stackWidth() {
      if (!this.stackWidth) {
        return this.cardWidth + this.paddingHorizontal * 2
      } else if (typeof this.stackWidth === 'number') {
        return this.stackWidth
      }

      return this.width || this.$el.clientWidth
    },
    _maxVisibleCampaigns() {
      return this.campaigns.length > this.maxVisibleCards
        ? this.maxVisibleCards
        : this.campaigns.length
    },
    elementXPosOffset() {
      return this.$el.getBoundingClientRect().x
    },
    elementYPosOffset() {
      return 20
    },
    isTouch() {
      return 'ontouchstart' in window
    },
    dragEvent() {
      return this.isTouch ? 'touchmove' : 'mousemove'
    },
    touchStartEvent() {
      return this.isTouch ? 'touchstart' : 'mousedown'
    },
    touchEndEvent() {
      return this.isTouch ? 'touchend' : 'mouseup'
    },
    stackRestPoints() {
      return this.campaigns.map((_, index) => {
        const xOffset =
          document.getElementById('pageLogo').getBoundingClientRect().left +
          (this.cardWidth / 2 - 100) * index

        if (this.isMobile) {
          return {
            x: 0,
            y: index === 1 ? 100 : -30 * index + this.mobileYOffset,
          }
        } else {
          return {
            x: xOffset,
            y: 0,
          }
        }
      })
    },
    cardDefaults() {
      return this.campaigns.map((_, index) => {
        const xPos = this.stackRestPoints[index].x
        const yPos = this.stackRestPoints[index].y
        let isMobile = false
        if (document.getElementById('globalHeader').clientWidth < 768) {
          isMobile = true
        }

        return {
          xPos: !isMobile
            ? index < this._maxVisibleCampaigns
              ? xPos
              : document.getElementById('pageLogo').getBoundingClientRect()
                  .left +
                this.cardWidth * (index - 1)
            : 0,
          yPos: isMobile
            ? index === 1
              ? 100
              : this.mobileYOffset + 10 * index * -1
            : 50,
          rotate:
            index !== 0
              ? Math.floor(Math.random() * (5 - 1 + 1) - 1) *
                (Math.round(Math.random()) ? 1 : -1)
              : 0,
          width: isMobile
            ? window.innerWidth - this.paddingHorizontal * 2
            : this.cardWidth,
          zIndex:
            index !== this.stack.length
              ? this.campaigns.length + index * -1
              : 0,
          isDragging: this.isDragging,
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
    window.addEventListener('resize', this.handleResize)
    this.$el.addEventListener(this.touchStartEvent, this.onTouchStart, {
      passive: true,
    })
    document.addEventListener(this.touchEndEvent, this.onTouchEnd)
  },
  methods: {
    getContrastColor(color) {
      const bgColor = new TinyColor(color)
      const fgColor = new TinyColor('#FFFFFF')
      const altfgColor = new TinyColor('#222329')

      const test1 = readability(bgColor, fgColor)
      const test2 = readability(bgColor, altfgColor)

      return (test1 + 5 > test2) ? 'contrast' : 'white'
    },
    init() {
      this.stack = this.campaigns

      // Move first element to end of stack
      this.stack.push(this.stack.shift())

      document.documentElement.style.setProperty(
        '--highlight',
        this.stack[this.stack.length-1].color
      )

      document.documentElement.style.setProperty(
        '--highlight-contrast',
        this.getContrastColor(this.stack[this.stack.length-1].color)
      )

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
            document.documentElement.style.setProperty(
              '--highlight',
              card.color
            )
            document.documentElement.style.setProperty(
              '--highlight-contrast',
              this.getContrastColor(card.color)
            )
          }
          return {
            ...card,
            ...this.cardDefaults[index],
          }
        })
      })
    },
    handleResize: debounce(function () {
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
      const activeCard = this.stack[0]
      const activeCardRestPoint = this.stackRestPoints[this.activeCardIndex]
      const distanceTravelledX = activeCard.xPos - activeCardRestPoint.x
      const minDistanceToTravel =
        (this.cardWidth + this.paddingHorizontal) / (1 / this.sensitivity)

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
          : Math.floor(Math.random() * (3 - 1 + 1) - 1) *
            (Math.round(Math.random()) ? 1 : -1)

        return {
          ...campaign,
          ...this.cardDefaults[index],
          xPos,
          yPos,
          rotate,
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
    onTouchEnd(e) {
      if (
        !this.isMobile &&
        Math.abs(this.getDragXPos(e) - this.dragStartX) < 10
      ) {
        this.$router.push(this.touchElement)
      }
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
    gotoStackItem(campaignId, url) {
      if (campaignId !== this.stack[0].id) {
        do {
          this.onNext()
        } while ( this.stack[0].id !== campaignId)
      } else {
        this.$router.push(url)
      }
    }
  },
}
</script>

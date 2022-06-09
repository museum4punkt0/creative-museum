<template>
  <div class="vue-card-stack__wrapper">
    <div
      class="vue-card-stack__stack"
      :style="{
        height: `${cardHeight + paddingVertical * 2}px`,
        width: containerWidth,
      }"
    >
      <div
        v-for="(card, index) in stack"
        :key="card._id"
        class="vue-card-stack__card"
        :style="{
          opacity: card.opacity,
          display: card.display,
          top: `${card.yPos}px`,
          width: `${card.width}px`,
          height: `${card.height}px`,
          zIndex: card.zIndex,
          transition: `transform ${
            isDragging ? 0 : speed
          }s ease,   ${speed}s ease`,
          'transform-origin': 'center center',
          transform: `
            rotate(${card.rotate}deg)
            translate(${card.xPos}px, 0)
          `,
        }"
      >
        <slot :card="{ ...card, $index: index }" name="card"></slot>
      </div>
    </div>
    <slot
      name="nav"
      v-bind="{ activeCardIndex: originalActiveCardIndex, onNext, onPrevious }"
    ></slot>
  </div>
</template>
<script>
import { debounce } from '@/utilities/debounce';

export default {
  name: 'CampaignCardStack',
  props: {
    cards: {
      type: Array,
      default: () => [],
    },
    cardHeight: {
      type: Number,
      default: () => 600,
    },
    stackWidth: {
      type: [Number, String],
      default: () => '100%',
    },
    sensitivity: {
      type: Number,
      default: () => 0.25,
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
      dragStartX: 0,
      dragStartY: 0,
      isDraggingRight: false,
      isMobile: false,
      cardWidth: 500
    };
  },
  computed: {
    _stackWidth() {
      if (!this.stackWidth) {
        return this.cardWidth + this.paddingHorizontal * 2;
      } else if (typeof this.stackWidth === "number") {
        return this.stackWidth;
      }

      return this.width || this.$el.clientWidth;
    },
    _maxVisibleCards() {
      return this.cards.length > this.maxVisibleCards
        ? this.maxVisibleCards
        : this.cards.length - 1;
    },
    containerWidth() {
      if (!this.stackWidth) {
        return `${this.cardWidth + this.paddingHorizontal * 2}px`;
      } else if (typeof this.stackWidth === "number") {
        return `${this.stackWidth}px`;
      }

      return this.stackWidth;
    },
    elementXPosOffset() {
      return this.$el.getBoundingClientRect().x;
    },
    isTouch() {
      return "ontouchstart" in window;
    },
    dragEvent() {
      return this.isTouch ? "touchmove" : "mousemove";
    },
    touchStartEvent() {
      return this.isTouch ? "touchstart" : "mousedown";
    },
    touchEndEvent() {
      return this.isTouch ? "touchend" : "mouseup";
    },
    stackRestPoints() {
      return this.cards.map((item, index) => {
        const offset = this.xPosOffset * index;

        if (!index) {
          return this.cardWidth * -1;
        } else if (index === 1) {
          return 0;
        } else {
          return (
            offset - this.paddingHorizontal
          );
        }
      });
    },
    cardDefaults() {
      return this.cards.map((card, index) => {
        const xPos = this.stackRestPoints[index];

        return {
          opacity: index > 0 && index < this._maxVisibleCards ? 1 : 0,
          display: index < this._maxVisibleCards + 1 ? "block" : "none",
          xPos: index < this._maxVisibleCards ? xPos : xPos - this.xPosOffset,
          yPos: this.paddingVertical,
          rotate: index > 0 ? Math.floor(Math.random() * ( 2.5 - -2.5 )) : 0,
          width: this.cardWidth,
          height: this.cardHeight,
          zIndex: this.cards.length + index * -1,
          isDragging: this.isDragging
        };
      });
    },
    xPosOffset() {
      return (
        (this._stackWidth - this.paddingHorizontal * 2 - this.cardWidth) /
        (this._maxVisibleCards - 2)
      );
    },
    originalActiveCardIndex() {
      if (this.stack[this.activeCardIndex]) {
        return this.stack[this.activeCardIndex]._index;
      }

      return 0;
    },
  },
  watch: {
    _maxVisibleCards: {
      handler() {
        this.rebuild();
      },
    },
  },
  mounted() {
    this.init();
    window.addEventListener("resize", this.handleResize);
    this.$el.addEventListener(this.touchStartEvent, this.onTouchStart);
    document.addEventListener(this.touchEndEvent, this.onTouchEnd);
  },
  methods: {
    init() {
      // move bottom card to top of stack (positioned offscreen)
      // eslint-disable-next-line vue/no-mutating-props
      this.cards.unshift(this.cards.pop());

      this.stack = this.cards.map((card, index) => {
        return {
          _id: new Date().getTime() + index,
          _index: index,
          ...card,
          ...this.cardDefaults[index],
        };
      });
      if (process.client) {
        if (window.innerWidth < 768) {
          this.isMobile = true
          this.cardWidth = window.innerWidth - this.paddingHorizontal
        }
      }
    },
    rebuild() {
      this.$nextTick(() => {
        this.stack = this.stack.map((card, index) => {
          return {
            ...card,
            ...this.cardDefaults[index],
          };
        });
      });
    },
    handleResize: debounce(function() {
      this.width = this.$el.clientWidth;
      this.rebuild();
    }, 250),
    onNext() {
      const cardToMoveToBottomOfStack = this.stack.shift();
      this.stack.push(cardToMoveToBottomOfStack);
      this.rebuild();
    },
    onPrevious() {
      const cardToMoveToTopOfStack = this.stack.pop();
      this.stack.unshift(cardToMoveToTopOfStack);
      this.rebuild();
    },
    updateStack() {
      const activeCard = this.stack[this.activeCardIndex];
      const activeCardRestPoint = this.stackRestPoints[this.activeCardIndex];
      const distanceTravelled = activeCard.xPos - activeCardRestPoint;
      const minDistanceToTravel =
        (this.cardWidth + this.paddingHorizontal) / (1 / this.sensitivity);

      this.$emit("move", 0);

      if (this.isDraggingRight) {
        if (distanceTravelled > minDistanceToTravel) {
          this.onPrevious();
        } else {
          this.rebuild();
        }
      } else if (distanceTravelled * -1 > minDistanceToTravel) {
          this.onNext();
        } else {
          this.rebuild();
        }
    },
    moveStack(dragXPos) {
      const activeCardOffset = dragXPos - this.dragStartX;

      this.$emit(
        "move",
        activeCardOffset / (this.cardWidth + this.paddingHorizontal)
      );

      if (this.isDraggingLeft) {
        this.activeCardIndex = 1;
      } else {
        this.activeCardIndex = 0; // first card is positioned offscreen
      }

      this.stack = this.stack.map((card, index) => {
        const isActiveCard = index === this.activeCardIndex;
        const xPos = isActiveCard
          ? this.cardDefaults[index].xPos + activeCardOffset
          : this.cardDefaults[index].xPos +
            (this.xPosOffset / (this.cardWidth + this.paddingHorizontal)) *
              activeCardOffset;

        const rotate = isActiveCard
          ? '0'
          : Math.floor(Math.random() * ( 2.5 - -2.5 ))

        return {
          ...card,
          ...this.cardDefaults[index],
          xPos,
          rotate,
          opacity:
            index === 0 && !this.isDraggingRight
              ? 1
              : this.cardDefaults[index].opacity,
        };
      });
    },
    getDragXPos(e) {
      return this.isTouch ? e.touches[0].clientX : e.clientX;
    },
    getDragYPos(e) {
      return this.isTouch ? e.touches[0].clientY : e.clientY;
    },
    onTouchStart(e) {
      this.isDragging = true;
      this.dragStartX = this.getDragXPos(e) - this.elementXPosOffset;
      this.dragStartY = this.getDragYPos(e);

      document.addEventListener(this.dragEvent, this.onDrag);
    },
    onTouchEnd() {
      this.isDragging = false;
      this.dragStartX = 0;
      this.dragStartY = 0;

      document.removeEventListener(this.dragEvent, this.onDrag);
      this.updateStack();
    },
    onDrag(e) {
      const dragXPos = this.getDragXPos(e) - this.elementXPosOffset;

      this.isDraggingRight = dragXPos > this.dragStartX;
      this.moveStack(dragXPos);
    },
  },
};
</script>
<style scoped>
.vue-card-stack__wrapper {
  position: relative;
}

.vue-card-stack__stack {
  position: relative;
  overflow: hidden;
}

.vue-card-stack__card {
  position: absolute;
  transform-origin: 0 50%;
  cursor: grab;
}
</style>
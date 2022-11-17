
<style lang="postcss" scoped>
div /deep/ .content {
  h1 {
    @apply font-medium text-3xl my-6 relative pl-10 block;
    &:before {
      @apply content-[''] block absolute w-6 h-[3px] bg-white left-0 top-1/2 transform -translate-y-[-3px];
    }
    &:after {
      @apply content-[''] block absolute w-4 h-4 left-0 top-1/2 transform -translate-y-[9px] -rotate-45 border-t-3 border-t-white border-l-3 border-l-white;
    }
    &:focus {
      @apply outline-none;
    }
  }
  p {
    @apply mb-6;
    a {
      @apply text-$highlight;
    }
  }
  ul li {
    @apply mb-6;
  }
}
</style>

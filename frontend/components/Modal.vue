<template>
  <div
    w:pos="fixed"
    w:top="15 lg:0"
    w:right="0"
    w:bottom="0"
    w:left="0"
    w:backdrop="~ lg:blur-lg"
    w:z="40 lg:100"
  >
    <div
      class="modal"
      w:bg="grey"
      w:pos="fixed"
      w:top="15 lg:1/2"
      w:right="0 lg:auto"
      w:left="0 lg:1/2"
      w:bottom="0 lg:auto"
      w:border="rounded-xl"
      w:transform="lg:~ lg:-translate-x-1/2 lg:-translate-y-1/2"
    >
      <slot></slot>
    </div>
  </div>
</template>
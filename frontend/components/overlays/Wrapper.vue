<template>
  <div>
    <OverlaysLogin v-if="!$auth.loggedIn" />
    <OverlaysProfileUpdate v-if="$auth.loggedIn" />
  </div>
</template>
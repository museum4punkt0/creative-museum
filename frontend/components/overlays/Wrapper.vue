<template>
  <div>
    <OverlaysLogin v-if="!$auth.loggedIn" />
    <OverlaysProfileUpdate v-if="$auth.loggedIn" />
    <OverlaysNotifications v-if="$auth.loggedIn" />
  </div>
</template>
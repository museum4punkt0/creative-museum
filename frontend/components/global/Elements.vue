<template>
  <div>
    <GlobalLogin v-if="!$auth.loggedIn" />
    <GlobalProfileUpdate v-if="$auth.loggedIn" />
  </div>
</template>
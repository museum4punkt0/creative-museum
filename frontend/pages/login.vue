<template>
  <div>
    <h1 w:text="primary 2xl bold" w:p="7" @click="login">Login</h1>
  </div>
</template>
<script>
export default {
  methods: {
    login () {
      this.$auth.login()
    }
  }
}
</script>

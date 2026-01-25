<template>
  <div class="auth-layout">
    <div class="form-signin">
      <slot/>
    </div>
  </div>
</template>

<script setup>

</script>

<style scoped>
.auth-layout{
  height: 100vh;
  display: flex;
  align-items: center;
  padding-top: 40px;
  padding-bottom: 40px;
  text-align: center;
}

.form-signin{
  width: 100%;
  max-width: 330px;
  padding: 15px;
  margin: auto;
}
</style>
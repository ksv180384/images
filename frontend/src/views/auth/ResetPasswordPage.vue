<template>
  <form>
    <h1 class="h3 mb-3 fw-normal">Изменить пароль</h1>

    <div class="form-floating my-2">
      <input type="password" class="form-control" id="floatingPassword" placeholder="Новый пароль">
      <label for="floatingPassword">Новый пароль</label>
    </div>
    <div class="form-floating my-2">
      <input type="password" class="form-control" id="floatingPasswordConfirm" placeholder="Подтвердите пароль">
      <label for="floatingPasswordConfirm">Подтвердите пароль</label>
    </div>

    <button class="w-100 btn btn-lg btn-primary" type="submit">Отправить</button>

    <p class="mt-3 text-muted">
      <router-link :to="{ name: 'login' }">
        Авторизоваться
      </router-link>
    </p>

  </form>
</template>

<script setup>

</script>

<style scoped>

</style>
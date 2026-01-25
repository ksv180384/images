<template>
  <form @submit.prevent="submitForm">
    <h1 class="h3 mb-3 fw-normal">Регистрация</h1>

    <div class="form-floating my-2">
      <InputForm
        v-model="email"
        id="floatingInput"
        label="Email"
        type="email"
        placeholder="name@example.com"
        :error_message="errorsRegistration.email"
      />

    </div>
    <div class="form-floating my-2">
      <InputForm
        v-model="password"
        id="floatingPassword"
        label="Пароль"
        type="password"
        placeholder="Введите пароль"
        :error_message="errorsRegistration.password"
      />
    </div>
    <div class="form-floating my-2">
      <InputForm
        v-model="passwordConfirm"
        id="floatingPasswordConfirm"
        label="Подтвердите пароль"
        type="password"
        placeholder="Подтвердите пароль"
      />
    </div>

    <BtnStandart
      class="w-100"
      style-type="primary"
      type="submit"
      :is-load="isLoadRequest"
      big
    >
      Зарегистрироваться
    </BtnStandart>

    <p class="mt-3">
      <router-link :to="{ name: 'login' }">
        Авторизоваться
      </router-link>
    </p>

  </form>
</template>

<script setup>
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import { registrationRequest } from '@/services/api_client';

import InputForm from '@/components/form/InputForm.vue';
import BtnStandart from '@/components/form/BtnStandart.vue';
import { useAuthStore } from '@/stores/auth';

const router = useRouter();
const email = ref('');
const password = ref('');
const passwordConfirm = ref('');
const errorsRegistration = ref({});
const isLoadRequest = ref(false);

const authStore = useAuthStore();

const submitForm = async () => {
  isLoadRequest.value = true;
  try{
    await registrationRequest({
      email: email.value,
      password: password.value,
      password_confirmation: passwordConfirm.value
    });
    authStore.setAuthUser(true);
    router.push('/')
  } catch (e) {
    if(e?.response?.errors_messages){
      errorsRegistration.value = e.response.errors_messages;
    }
    console.error(e);
  } finally {
    isLoadRequest.value = false;
  }
}
</script>

<style scoped>

</style>
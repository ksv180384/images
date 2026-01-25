<template>
  <form>
    <h1 class="h3 mb-3 fw-normal">Забыли пароль?</h1>

    <div v-if="allerMessage" class="alert alert-success" role="alert">
      {{ allerMessage }}
    </div>

    <form @submit.prevent="submitForm">
      <div class="form-floating my-2">
        <InputForm
          v-model="email"
          id="floatingEmail"
          type="email"
          label="Введите email"
          placeholder="Введите email"
          :disabled="isLoadRequest"
          :error_message="errorsRequest.email"
        />
      </div>

      <BtnStandart
        class="w-100"
        @click="submitForm"
        style-type="primary"
        :big="true"
        :disabled="isLoadRequest"
      >
        Отправить
      </BtnStandart>
    </form>

    <p class="mt-3 text-muted">
      <router-link :to="{ name: 'login' }">
        Авторизоваться
      </router-link>
    </p>

  </form>
</template>

<script setup>
import { ref } from 'vue';
const email = ref('');
const allerMessage = ref('');
const errorsRequest = ref({});
const isLoadRequest = ref(false);

import InputForm from '@/components/form/InputForm.vue';
import BtnStandart from '@/components/form/BtnStandart.vue';
import { forgotPasswordRequest } from '@/services/api_client';

const submitForm = async () => {
  if(isLoadRequest.value){
    return true;
  }

  isLoadRequest.value = true;
  errorsRequest.value = {};
  try{
    const resRequest = await forgotPasswordRequest({ email: email.value });
    allerMessage.value = resRequest.status;
  } catch (e) {
    if(e?.response?.errors_messages){
      errorsRequest.value = e.response.errors_messages;
    }
    console.error(e);
  } finally {
    isLoadRequest.value = false;
  }
}
</script>

<style scoped>

</style>
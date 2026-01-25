<template>
  <h1 class="h3 mb-3 fw-normal">Авторизуйтесь пожалуйста</h1>

  <el-form
      ref="refForm"
      :model="form"
      :rules="rules"
      @submit.prevent="submitForm"
  >
    <div class="form-floating my-2">
      <el-form-item prop="email">
        <el-input
            v-model="form.email"
            size="large"
            placeholder="Email"
        />
      </el-form-item>
    </div>
    <div class="form-floating my-2">
      <el-form-item prop="password">
        <el-input
            v-model="form.password"
            size="large"
            placeholder="Пароль"
            type="password"
        />
      </el-form-item>
    </div>

    <div class="my-3">
      <el-checkbox v-model="form.remember" label="Запомнить" size="large" />
    </div>

    <el-button
        class="w-100"
        type="primary"
        plain
        :loading="isLoadRequest"
        native-type="submit"
    >
      Авторизоваться
    </el-button>
  </el-form>

  <p class="mt-3 text-muted">
    <router-link :to="{ name: 'registration' }">Зарегистрироваться</router-link>
  </p>
  <p class="mt-2">
    <router-link :to="{ name: 'forgot_password' }">Забыли пароль?</router-link>
  </p>
</template>

<script setup lang="ts">
import { ref, reactive } from 'vue';
import { useRouter } from 'vue-router';
import { useAuthStore } from '@/stores/auth';
import api from '@/services/api';

const authStore = useAuthStore();
const router = useRouter();
const refForm = ref(null);
const isLoadRequest = ref(false);
const form = reactive({
  email: '',
  password: '',
  remember: true,
});
const rules = reactive<FormRules<typeof ruleForm>>({
  email: [{ required: true, message: 'Email обязательно для заполнения', trigger: 'blur' }],
  password: [{ required: true, message: 'Введите пароль', trigger: 'blur' }],
});

const submitForm = async () => {

  const isValidForm = await refForm.value.validate((valid) => valid);
  if(!isValidForm){
    return;
  }

  isLoadRequest.value = true;
  try{
    await api.auth.login(form);
    authStore.setAuthUser(true);
    router.push({ path: '/' });
  } catch (e) {
    if(e?.response?.errors_messages){
      errorsLogin.value = e.response.errors_messages;
    }
    console.error(e);
  } finally {
    isLoadRequest.value = false;
  }
}

</script>

<style scoped>

</style>
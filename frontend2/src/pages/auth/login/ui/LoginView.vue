<script setup lang="ts">

import { reactive, ref } from 'vue';
import { useRouter } from 'vue-router';
import { REGISTER_ROUTE } from '@/pages/auth/register';
import { HOME_LINK } from '@/shared/config';
import { login } from '@/pages/auth/login/api';

import type { FormInstance, FormRules } from 'element-plus';

interface RuleForm {
  email: string,
  password: string,
}

const router = useRouter();
const isSubmittingForm = ref(false);
const refForm = ref<FormInstance>();
const form = reactive<RuleForm>({
  email: '',
  password: '',
});

const rules = reactive<FormRules<RuleForm>>({
  email: [
    { required: true, message: 'Введите Email', trigger: 'blur' },
  ],
  password: [
    { required: true, message: 'Введите пароль', trigger: 'blur',},
  ],
});

const submitForm = async (): Promise<void> => {
  if (!refForm.value) return;

  const isFormValid = await refForm.value.validate();
  if(!isFormValid){
    return;
  }

  isSubmittingForm.value = true;

  const result = await login(form);

  isSubmittingForm.value = false;
  // router.push({ name: HOME_LINK.name });
}
</script>

<template>
  <div class="shadow-xl rounded-lg p-4">
    <el-form
      ref="refForm"
      :model="form"
      :rules="rules"
      @submit.prevent="submitForm"
    >
      <h1 class="text-center font-semibold text-xl">Авторизация</h1>

      <div class="form-floating my-2">
        <el-form-item label="Email" prop="email" label-position="top">
          <el-input
            v-model="form.email"
            type="email"
            placeholder="name@example.com"
            clearable
          />
        </el-form-item>

      </div>
      <div class="form-floating my-2">
        <el-form-item label="Пароль" prop="password" label-position="top">
          <el-input
            v-model="form.password"
            type="password"
            autocomplete="off"
            placeholder="Введите пароль"
            show-password
            clearable
          />
        </el-form-item>
      </div>

      <el-button
        class="w-full mt-4"
        type="primary"
        style-type="primary"
        native-type="submit"
        :loading="isSubmittingForm"
        size="large"
      >
        Авторизоваться
      </el-button>

      <div class="flex justify-between mt-4 text-gray-600 text-sm">
        <div>
          <router-link :to="{ name: 'home' }" class="link">На главную</router-link>
        </div>
        <div class="">
          У вас нет аккаунта?
          <router-link
            :to="{ name: REGISTER_ROUTE.name }"
            class="link"
          >
            Зарегистрироваться
          </router-link>
        </div>
      </div>
    </el-form>
  </div>
</template>

<style scoped>

</style>


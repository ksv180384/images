<script setup lang="ts">

import { reactive, ref } from 'vue';
import { useRouter } from 'vue-router';
import type { FormInstance, FormRules } from 'element-plus';
import { register } from '@/pages/auth/register/api';
import { LOGIN_ROUTE } from '@/pages/auth/login';
import { HOME_LINK } from '@/shared/config';

interface RuleForm {
  email: string,
  password: string,
  password_confirm: string,
}

const router = useRouter();
const isSubmittingForm = ref(false);
const refForm = ref<FormInstance>();
const form = reactive<RuleForm>({
  email: '',
  password: '',
  password_confirm: '',
});

const validatePassword = (rule: any, value: any, callback: any) => {
  if (value !== form.password_confirm) {
    callback(new Error('Неверно подтвержден пароль'));
  } else {
    callback();
  }
}

const rules = reactive<FormRules<RuleForm>>({
  email: [
    { required: true, message: 'Введите Email', trigger: 'blur' },
  ],
  password: [
    { required: true, message: 'Введите пароль', trigger: 'blur',},
    { validator: validatePassword, trigger: 'blur',},
  ],
  password_confirm: [
    { required: true, message: 'Подтвердите пароль', trigger: 'blur',},
  ],
});

const submitForm = async (): Promise<void> => {
  if (!refForm.value) {
    return;
  }

  const isFormValid = await refForm.value.validate();
  if(!isFormValid){
    return;
  }

  isSubmittingForm.value = true;

  const result = await register(form);

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
      <h1 class="text-center font-semibold text-xl">Регистрация</h1>

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
      <div class="form-floating my-2">
        <el-form-item label="Подтвердите пароль" prop="password_confirm" label-position="top">
          <el-input
            v-model="form.password_confirm"
            type="password"
            autocomplete="off"
            placeholder="Подтвердите пароль"
            show-password
            :disabled="!form.password"
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
        Зарегистрироваться
      </el-button>

      <div class="flex justify-between mt-4 text-gray-600 text-sm">
        <div>
          <router-link :to="{ name: 'home' }" class="link">На главную</router-link>
        </div>
        <div class="">
          Уже есть аккаунт?
          <router-link
            :to="{ name: LOGIN_ROUTE.name }"
            class="link"
          >
            Авторизоваться
          </router-link>
        </div>
      </div>

    </el-form>
  </div>
</template>

<style scoped>

</style>

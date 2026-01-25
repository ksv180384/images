<template>
  <div class="category-form">
    <form @submit.prevent="submitCard">
      <div class="d-flex align-items-center">
        <div class="flex-grow-1">
          <div class="form-floating mb-3">
            <InputForm
              v-model="title"
              id="inputTitle"
              label="Название"
              type="text"
              placeholder="Введите название"
              :error_message="errorsRequest.title"
              :disabled="isLoad"
            />
          </div>
          <div class="form-floating mb-3">
            <InputForm
              v-model="url"
              id="inputImg"
              label="Введите url"
              type="text"
              placeholder="Введите url"
              :error_message="errorsRequest.url"
              :disabled="isLoad"
            />
          </div>

          <div class="mb-3">
            <InputTextarea
              id="inputDescription"
              v-model="description"
              rows="3"
              label="Описание"
              placeholder="Введите описание..."
              :error_message="errorsRequest.description"
              :disabled="isLoad"
            />
          </div>
        </div>
        <div v-if="url" class=" img-container ms-4 rounded overflow-hidden">
          <img :src="url" :alt="title"/>
        </div>
      </div>

      <button type="submit" class="btn btn-primary" :disabled="isLoad">
        Сохранить
      </button>
    </form>
  </div>
</template>

<script setup>

import { ref, defineEmits } from 'vue';
import api from '@/services/api.ts';

import InputForm from '@/components/form/InputForm.vue';
import InputTextarea from '@/components/form/InputTextarea.vue';

const props = defineProps({
  category: Object,
});

const emits = defineEmits(['onSubmitForm']);

const isLoad = ref(false);

const id = ref(props?.category?.id);
const title = ref(props?.category?.title);
const url = ref(props?.category?.url);
const description = ref(props?.category?.description);
const errorsRequest = ref({});

// Сохраняем карточку
const submitCard = async () => {
  const category = { id: id.value, title: title.value, url: url.value, description: description.value };
  let resCategory = {};

  try{
    isLoad.value = true;

    if(category.id){
      resCategory = await api.category.updateCategory(category);
    }else{
      resCategory = await api.category.createCategory(category);
    }

    emits('onSubmitForm', resCategory);

  } catch (e) {
    if(e?.response?.errors_messages){
      errorsRequest.value = e.response.errors_messages;
    }
    console.error(e);
  } finally {
    isLoad.value = false;
  }
}
</script>

<style scoped>
.img-container{
  max-width: 200px;
  max-height: 740px;
}

.img-container > img{
  width: 100%;
  object-fit: contain;
}
</style>


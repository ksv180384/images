<template>
  <div class="tags-control">
    <form @submit.prevent="addNewTag">
      <div class="input-group mb-3">
        <input
          v-model="newTagName"
          type="text"
          class="form-control"
          placeholder="Введите название тега"
          aria-label="Введите название тега"
          aria-describedby="btnAddTags"
        />
        <button
          class="btn btn-outline-secondary"
          type="submit"
          id="btnAddTags"
          title="Добавить новый тег"
          :disabled="loadAddNewTag"
        >
          <i class="bi bi-plus-square"></i>
        </button>
      </div>
    </form>

    <el-scrollbar v-if="showTags.length" class="tags-list-block list-group">
      <ul>
        <template v-for="tag in showTags" :key="tag.id">
          <TagItem
            :tag-item="tag"
            @onDelete="deleteTag"
            @onActivate="activateTag"
          />
        </template>
      </ul>
    </el-scrollbar>

    <div class="py-2 text-center">
      <BtnStandart
        @click="save"
        style-type="primary"
        type="button"
        :disabled="isLoadSave"
        :is-load="isLoadSave"
        outline
        mini
      >
        Сохранить {{ countActivate }}
      </BtnStandart>
    </div>

  </div>

</template>

<script setup>

import { ref, toRef, computed, defineEmits } from 'vue';
import api from '@/services/api';

import TagItem from '@/components/modules/tags_control/TagItem.vue';
import BtnStandart from '@/components/form/BtnStandart.vue';

const props = defineProps({
  tags: { type: Array, default: [] },
  isLoadSave: { type: Boolean, default: false }
});

const emits = defineEmits(['onDeleteTag', 'onActivateTag', 'onSave']);
const tagsList = toRef(props, 'tags');
const isLoadSave = toRef(props, 'isLoadSave');
const newTagName = ref('');
const countActivate = ref(0);
const loadAddNewTag = ref(false);


const showTags = computed(() => {
  return tagsList.value.filter((tag) => {
    return tag.title
      .toLowerCase()
      .indexOf(newTagName.value.toLowerCase()) != -1
  })
});

const addNewTag = async () => {
  if(loadAddNewTag.value || isNotUniqTag(newTagName.value)){
    return true;
  }
  loadAddNewTag.value = true;
  try{
    const resAddTag = await api.tag.addTag({ title: newTagName.value });
    tagsList.value.unshift(resAddTag);
  } catch (e) {
    console.error(e);
  } finally {
    newTagName.value = '';
    loadAddNewTag.value = false;
  }
}

const activateTag = (tagId, isActivate) => {
  countActivate.value = isActivate ? countActivate.value + 1 : countActivate.value - 1;
  emits('onActivateTag', tagId, isActivate);
}

const save = () => {
  emits('onSave');
}

const deleteTag = (id) => {
  emits('onDeleteTag', id);
}

// Проверяем название тега на уникальность
const isNotUniqTag = (tagName) => {
  return !!tagsList.value.filter((item) => item.title.toLowerCase() === tagName.toLowerCase()).length
}

</script>

<style scoped>
.tags-control{
  height: 100%;
}

.tags-list-block{
  max-height: calc(100% - 94px);
  overflow: auto;
}

.tags-list-block ul{
  padding: 0;
  border-radius: 8px;
  overflow: hidden;
}
</style>
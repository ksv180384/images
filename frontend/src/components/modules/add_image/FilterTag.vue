<template>
  <el-scrollbar class="filter-tag">
    <ul v-if="tags">
      <template v-for="tag in tags">
        <FilterTagItem
          :tag-item="tag"
          @onChange="changeSelectTag"
        />
      </template>
    </ul>
  </el-scrollbar>
</template>

<script setup>

import { toRef, ref, defineEmits } from 'vue';
import FilterTagItem from '@/components/modules/add_image/FilterTagItem.vue';

const props = defineProps({
  tagsList: { type: Array, default: [] },
});

const emits = defineEmits(['onChange']);
const tags = toRef(props, 'tagsList');
const selectList = ref([]);

const changeSelectTag = (select) => {
  const el = selectList.value.find(item => item.id === select.id);
  if(el){
    if(select.active){
      selectList.value = selectList.value.map(item => {
        if(item.id === select.id){
          return select;
        }
        return { id: item.id, active: item.active };
      });
    }else{
      selectList.value = selectList.value.filter(item => item.id !== select.id);
    }
  }else{
    selectList.value.push(select);
  }
  emits('onChange', selectList.value);
}

</script>

<style scoped>
.filter-tag{
  /*width: 100%;*/
  height: 100%;
  //overflow: auto;
}

.filter-tag ul{
  text-align: left;
  list-style: none;
  padding: 0;
}
</style>
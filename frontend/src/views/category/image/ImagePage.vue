<template>
  <div class="image-page">
    <div
        ref="imgBlock"
        :class="imageStyleClass"
        @mousedown="captureImage"
        @mouseup="releaseImage"
        @mouseout="releaseImage"
        @mousemove="onMouseMove"
    >
      <div v-if="Object.keys(image).length" class="image-container">
        <img :src="image.path_full" @dragstart="(e) => e.preventDefault()"/>

        <div v-if="positionsFaces">
          <div v-for="(position, index) in positionsFaces"
               class="positions-paces"
               :style="{ 'top': `${position[0]}px`, 'left': `${position[3]}px`, 'width': `${(position[1] - position[3])}px`, 'height': `${(position[2] - position[0])}px` } "
          >
            <input v-model="selectFacePosition" type="radio" name="select-face" :value="index">
            <div></div>
          </div>

          <div v-if="selectFacePosition !== false" class="face-control-block">
            <BtnStandart
              @click="saveSelectFacePosition"
              style-type="success"
              :disabled="loadSaveFace"
              :is-load="loadSaveFace"
              :outline="true"
            >
              Сохранить
            </BtnStandart>
            <button
              @click="resetSelectFacePosition"
              class="btn btn-sm btn-outline-danger"
              type="button"
            >
              Отмена
            </button>
          </div>
        </div>

      </div>

    </div>

    <div class="img-control-panel">
      <div class="img-control-block">
        <div @click="loadDetectedFace" class="btn-control" title="Определить лицо">
          <i v-if="!loadDetected" class="bi bi-person-bounding-box"></i>
          <div v-else class="spinner-border text-success" role="status"></div>
        </div>
        <div class="btn-control btn-show-face" @click="showFace" title="Показать лицо">
          <i v-if="!loadFace" class="bi bi-person-circle"></i>
          <div v-else class="spinner-border text-success" role="status"></div>
          <img v-if="faceImg" :src="faceImg" />
        </div>
        <div class="btn-control" @click="toggleImageFullSize" title="Полный размер">
          <i v-if="!imageFullSize" class="bi bi-arrows-fullscreen"></i>
          <i v-else class="bi bi-fullscreen-exit"></i>
        </div>
        <div class="btn-control" @click="getInfo" title="Информация об изображении">
          <i class="bi bi-info-circle"></i>
        </div>
        <el-tooltip content="Сохранить изображение в формате 512х512" placement="top" effect="light">
          <div class="btn-control" @click="saveFormat512" title="">
            <el-button :loading="isLoadingFormat512" link>512</el-button>
          </div>
        </el-tooltip>
    </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue';
import { useRoute } from 'vue-router';
import { usePageStore } from '@/stores/page';
// import { getImageById, getFace, detectedFace, saveFaceDetected, getImageInfo, saveFormat } from '@/services/api_admin';
import api from '@/services/api';

import BtnStandart from '@/components/form/BtnStandart.vue';

const route = useRoute();
const pageStore = usePageStore();
const image = ref({});
const imageFullSize = ref(false);
const captureImg = ref(false);
const imgBlock = ref(null);
const captureStartPosition = { x: 0, y: 0 };
const faceImg = ref('');
const detectedFacesList = ref([]);
const positionsFaces = ref([]);
const selectFacePosition = ref(false); // выбранные координаты лица
const loadDetected = ref(false);
const loadFace = ref(false);
const loadSaveFace = ref(false);
const isLoadingFormat512 = ref(false);

// TODO + Перенос картинок лиц и фрагментов в папку storage и добавление в модель
// TODO Обнаружение лица по определенной модели (можно выбрать модель)
// TODO Выделение лица в нужной части
// TODO Сохранение только фрагментов
// TODO Подгрузка изображений по скролу
// TODO Увеличение выделения лицы в %
// TODO Очистка матаданных
// TODO Разделение на категории
// TODO Добавление тегов

onMounted(() => {
  // loadData(route.params.id);
  console.log(pageStore?.data);
  image.value = pageStore?.data || {};
});

// const loadData = async (imageId) => {
//   const resultImage = await api.image.getImageById(imageId);
//   image.value = resultImage;
// }

// Меняем размер отображения картинки (полный размер - размер блока)
const toggleImageFullSize = () => {
  imageFullSize.value = !imageFullSize.value;
  positionsFaces.value = []; // Убираем подгруженные координаты лиц
  resetSelectFacePosition(); // Снимаем выделение лица
}

// Меняем класс стилей отображения картинки
const imageStyleClass = computed(() => {
  let res = captureImg.value && imageFullSize.value ? 'cursor-grabbing ' : '';
  res += imageFullSize.value ? 'image-page-full-size' : 'image-page-normal-size';
  return res
});

// Получаем изображение лица
const showFace = async () => {
  if(faceImg.value){
    faceImg.value = '';
    return true;
  }
  if(loadFace.value){
    return true
  }

  loadFace.value = true;

  try{
    const arImage = image.value.image.split('/');
    const imageName = arImage[arImage.length - 1];
    //const resFace = await getFace(route.params.category_id, imageName);
    // const resFace = await getFace(image.value.id, imageName);
    const resFace = await api.image_control_api.getFace(image.value.id, imageName);
    faceImg.value = resFace.image
  } catch (e) {
    console.error(e);
  } finally {
    loadFace.value = false;
  }

}

// Получаем координаты найденых лиц
const loadDetectedFace = async () => {
  if(loadDetected.value){
    return true;
  }

  if(Object.keys(positionsFaces.value).length){
    positionsFaces.value = [];
    resetSelectFacePosition();
    return true;
  }
  loadDetected.value = true;
  try{
    // const resDetectedFace = await detectedFace(route.params.category_id, imageName);
    // const resDetectedFace = await detectedFace(image.value.id);
    const resDetectedFace = await api.image_control_api.detectedFace(image.value.id);

    detectedFacesList.value = resDetectedFace;
    imageFullSize.value = true;
    positionsFaces.value = resDetectedFace
  }catch (e) {
    console.error(e);
  } finally {
    loadDetected.value = false;
  }
}

const captureImage = (e) => {
  captureImg.value = true;
  captureStartPosition.x = imgBlock.value.scrollLeft + e.pageX;
  captureStartPosition.y = imgBlock.value.scrollTop + e.pageY;
}

const releaseImage = () => {
  captureImg.value = false;
}

const onMouseMove = (e) => {
  if(!imageFullSize.value || !captureImg.value){
    return true;
  }

  const x = captureStartPosition.x - e.pageX;
  const y = captureStartPosition.y - e.pageY;

  imgBlock.value.scrollTo({
    top: y,
    left: x,
    behavior: 'instant',
  });
}

const resetSelectFacePosition = () => {
  selectFacePosition.value = false;
}

const saveSelectFacePosition = async () => {
  loadSaveFace.value = true;
  try{
    const position = detectedFacesList.value[selectFacePosition.value];
    const resFaceDetected = await api.image_control_api.saveFaceDetected(image.value.id, position);
  } catch (e) {

  } finally {
    loadSaveFace.value = false;
  }
}

const getInfo = async () => {
  // const res = await getImageInfo(image.value.id);
  const res = await api.image.getImageInfo(image.value.id);
}

const saveFormat512 = async () => {
  if(isLoadingFormat512.value){
    return true;
  }
  isLoadingFormat512.value = true;
  try{
    // const res = await saveFormat(image.value.id);
    const res = await api.image.saveFormat(image.value.id);
  } catch (e) {

  } finally {
    isLoadingFormat512.value = false;
  }

}

</script>

<style scoped>
.image-page{

}

.image-page-normal-size{
  text-align: center;
  height: calc(100vh - 69px);
}

.image-page-full-size{
  display: block;
  background-color: #fff;
  position: absolute;
  top: 0;
  left: 0;
  max-height: 100%;
  width: 100%;
  overflow: auto;
  cursor: grab;
  text-align: center;
}

.cursor-grabbing{
  cursor: grabbing !important;
}

.image-page img{
  flex: 1 1 100%;
  max-height: 100%;
}

.image-page-normal-size img{
  max-width: 100%;
}

.img-control-panel{
  position: fixed;
  top: calc(100vh / 2);
  transform: translateY(-50%);
  right: 20px;
  z-index: 2;
}

.img-control-block{

}

.btn-control{
  display: flex;
  justify-content: center;
  align-items: center;
  border-radius: 4px;
  border: 1px solid;
  cursor: pointer;
  width: 32px;
  height: 32px;
  margin-top: 4px;
  margin-bottom: 4px;
  background-color: #ffffff87;
  font-size: 22px;
  user-select: none;
}

.btn-control .spinner-border{
  width: 18px;
  height: 18px;
}

.btn-show-face{

}

.btn-show-face img{
  position: absolute;
  width: 70px;
  border-radius: 6px;
  left: -74px;
  border: 1px solid #ededed;
  box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
}

.positions-paces{
  position: absolute;
  border: 1px solid #fff;
  border-radius: 6px;
  overflow: hidden;
}

.positions-paces [type="radio"]{
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  cursor: pointer;
  z-index: 1;
  opacity: 0;
}

.positions-paces [type="radio"]+div{
  position: absolute;
  top:0;
  left: 0;
  width: 100%;
  height: 100%;
}

.positions-paces [type="radio"]:checked+div{
  background-color: #ffffff75;
}

.image-container{
  display: inline-block;
  position: relative;
  height: 100%;
}

.face-control-block{
  position: fixed;
  display: flex;
  top: 6px;
  right: 20px;
  border-radius: 4px;
  padding: 6px;
  background-color: #ffffffa1;
}

.face-control-block button{
  margin-left: 2px;
  margin-right: 2px;
}
</style>
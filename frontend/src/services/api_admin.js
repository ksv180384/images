import { postRequest, getRequest, getRequestPage } from './api';


// Категории

// Добавляем новую категорию
export const createCategory = async (card) => {
    return await postRequest('admin/category/create', card);
}

// Сохраняем данные категории
export const updateCategory = async (card) => {
    return await postRequest('admin/category/update', card);
}

// Изображения

// // Получаем все изображения категории
// export const getImagesByCategoryId = async (categoryId) => {
//     return await getRequest(`admin/category/${categoryId}/images`);
// }
//
// // Получаем изображение по id
// export const getImageById = async (imageId) => {
//     return await getRequest(`admin/image/${imageId}`);
// }
//
// // Получаем метеданные изображения
// export const imageFilter = async (filterData) => {
//     return await postRequest(`admin/image/filter`, filterData);
// }
//
// // Добавляем изображение
// export const addImage = async (formData) => {
//     return await postRequest('admin/image/create', formData, { headers: { "Content-Type": "multipart/form-data" } });
// }
//
// // Удаляем изображение
// export const deleteImage = async (id) => {
//     return await postRequest('admin/image/delete', { image_id: id } );
// }
//
// // Получаем теги прикрепленные к изображению
// export const getAttachTagsToImage = async (image_id) => {
//     return await getRequest(`admin/image/${image_id}/tags`);
// }
//
// // Сохраняем дату создания изображения (если фото, то дата, когда был сделан снимок)
// export const saveDateCreateImage = async (imageId, date) => {
//     return await postRequest(`admin/image/save-create-date`, { image_id: imageId, date: date });
// }
//
// // Получаем метеданные изображения
// export const getImageInfo = async (imageId) => {
//     return await getRequest(`admin/image/get-info/${imageId}`);
// }
//
// // Сохраняем изображение в формате
// export const saveFormat = async (imageId) => {
//     return await postRequest(`admin/image/save-format/${imageId}`);
// }
//
// // Получаем похожие изображения
// export const getShowSimilarRequest = async (imageId) => {
//     return await getRequest(`admin/image/similar/${imageId}`);
// }
//
// // Получаем похожие изображения
// export const getDateRangeImages = async (imageId) => {
//     return await getRequest(`admin/image/similar/${imageId}`);
// }


// Теги изображение

// // Добавление нового тега
// export const getTagImageAll = async () => {
//     return await getRequest('admin/image/tags');
// }
//
// // Добавление нового тега
// export const addTag = async (formData) => {
//     return await postRequest('admin/tag/create', formData);
// }
//
// export const deleteTag = async (tagId) => {
//     return await postRequest(`admin/tag/delete/${tagId}`);
// }
//
// // Присоединяем тег к картинке
// export const attachTagsToImage = async (data) => {
//     return await postRequest('admin/category/image/tag/attach-to-image', data);
// }
//
// // Получаем изображения определенных тегов и категории
// export const getImagesByTags = async (categoryId, tagsIds) => {
//     return await postRequest('admin/tags/attach-images-by-tags', { category_id: categoryId, tags_ids: tagsIds });
// }
//
// // Присоединяем теги к изображениям
// export const attachTags = async (tasIds, imagesIds) => {
//     return await postRequest('admin/tags/attach-images', { tags_ids: tasIds, images_ids: imagesIds });
// }


// Теги

// // Питон сервис
//
// // Получаем лицо с изобоажения
// export const getFace = async (imageId) => {
//     return await getRequest(`admin/detected/get-face?image_id=${imageId}`);
// }
//
// // Определяем координаты лиц на изображении
// export const detectedFace = async (imageId) => {
//     return await getRequest(`admin/detected/detected-faces?image_id=${imageId}`);
// }
//
// // Вырезаем и сохраняем лицо по координатам
// export const saveFaceDetected = async (imageId, position) => {
//     return await postRequest(`admin/detected/face-crop`, {
//         image_id: imageId,
//         position: position
//     });
// }
//
// // Поиск по изображению
// export const searchByPhoto = async (categoryId, imgBase64) => {
//
//     return await postRequest(`admin/detected/search-by-photo`, {
//         category_id: categoryId,
//         img_base64: imgBase64,
//     });
// }
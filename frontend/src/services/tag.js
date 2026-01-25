// Добавление нового тега
import { get, post } from '@/services/query';

const getTagImageAll = async () => {
    return await get('admin/image/tags');
}

// Добавление нового тега
const addTag = async (params) => {
    return await post('admin/tag/create', params);
}

// Удаляем тег
const deleteTag = async (id) => {
    return await post(`admin/tag/delete/${id}`);
}

// Присоединяем тег к картинке
const attachTagsToImage = async (params) => {
    return await post('admin/category/image/tag/attach-to-image', params);
}

// Получаем изображения определенных тегов и категории
const getImagesByTags = async (categoryId, tagsIds) => {
    return await post('admin/tags/attach-images-by-tags', { category_id: categoryId, tags_ids: tagsIds });
}

// Присоединяем теги к изображениям
const attachTags = async (tasIds, imagesIds) => {
    return await post('admin/tags/attach-images', { tags_ids: tasIds, images_ids: imagesIds });
}

export default {
    getTagImageAll,
    addTag,
    deleteTag,
    attachTagsToImage,
    getImagesByTags,
    attachTags,
};
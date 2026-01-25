import { get, post } from '@/services/query';
import { IImage } from '@/interfaces/IImage';

// Получаем все изображения категории
const getImagesByCategoryId = async (categoryId) => {
    return await get(`admin/category/${categoryId}/images`);
}

// Получаем изображение по id
const getImageById = async (imageId) => {
    return await get(`/admin/image/${imageId}`);
}

// Получаем метеданные изображения
const imageFilter = async (filterData) => {
    return await post(`admin/image/filter`, filterData);
}

// Добавляем изображение
const addImage = async (formData: FormData): Promise<IImage> => {
    return await post('admin/image/create', formData, { headers: { "Content-Type": "multipart/form-data" } })
}

// Удаляем изображение
const deleteImage = async (id) => {
    return await post('admin/image/delete', { image_id: id } );
}

// Получаем теги прикрепленные к изображению
const getAttachTagsToImage = async (image_id) => {
    return await get(`admin/image/${image_id}/tags`);
}

// Сохраняем дату создания изображения (если фото, то дата, когда был сделан снимок)
const saveDateCreateImage = async (imageId, date) => {
    return await post(`admin/image/save-create-date`, { image_id: imageId, date: date });
}

// Получаем метеданные изображения
const getImageInfo = async (imageId) => {
    return await get(`admin/image/get-info/${imageId}`);
}

// Сохраняем изображение в формате
const saveFormat = async (imageId) => {
    return await post(`admin/image/save-format/${imageId}`);
}

// Получаем похожие изображения
const getShowSimilarRequest = async (imageId) => {
    return await get(`admin/image/similar/${imageId}`);
}

// Получаем похожие изображения
const getDateRangeImages = async (imageId) => {
    return await get(`admin/image/similar/${imageId}`);
}

export default {
    getImagesByCategoryId,
    getImageById,
    imageFilter,
    addImage,
    deleteImage,
    getAttachTagsToImage,
    saveDateCreateImage,
    getImageInfo,
    saveFormat,
    getShowSimilarRequest,
    getDateRangeImages,
};
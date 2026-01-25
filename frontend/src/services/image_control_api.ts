import { get, post } from '@/services/query';
import { IImage } from '@/interfaces/IImage';
// Питон сервис

// Получаем лицо с изобоажения
const getFace = async (imageId) => {
    return await get(`admin/detected/get-face?image_id=${imageId}`);
}

// Определяем координаты лиц на изображении
const detectedFace = async (imageId) => {
    return await get(`admin/detected/detected-faces?image_id=${imageId}`);
}

// Вырезаем и сохраняем лицо по координатам
const saveFaceDetected = async (imageId, position) => {
    return await post(`admin/detected/face-crop`, {
        image_id: imageId,
        position: position
    });
}

// Поиск по изображению
const searchByPhoto = async (categoryId: number, imgBase64: string): Promise<IImage[]> => {
    return await post(`admin/detected/search-by-photo`, {
        category_id: categoryId,
        img_base64: imgBase64,
    });
}

export default {
    getFace,
    detectedFace,
    saveFaceDetected,
    searchByPhoto,
};
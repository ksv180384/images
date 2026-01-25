import axios from 'axios';
import { interceptors } from "./interceptors";

const apiPython = axios.create({
    //baseURL: 'http://imagines_python/detected-face',
    baseURL: 'http://localhost:8001/detected-face',
    headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
    }
});

interceptors(apiPython);

export const getFace = async (categoryId, fileName) => {
    const cat_id = encodeURI(categoryId);
    const file = encodeURI(fileName);
    return await apiPython.get(`/face?category_id=${cat_id}&file_name=${file}`);
}

export const detectedFace = async (categoryId, fileName) => {
    const cat_id = encodeURI(categoryId);
    const file = encodeURI(fileName);
    return await apiPython.get(`/detected?category_id=${cat_id}&file_name=${file}`);
}

export const saveFaceDetected = async (categoryId, fileName, position) => {

    return await apiPython.post(`/face-crop`, {
        category_id: categoryId,
        file_name: fileName,
        position: position,
    }, { headers: { "Content-Type": "multipart/form-data" } });
}

export const allFaceDetectedByCategory = async (categoryId) => {

    return await apiPython.post(`/face-detected-all-by-category`, {
        category_id: categoryId,
    }, { headers: { "Content-Type": "multipart/form-data" } });
}

// export const trainingSearchByPhoto = async (categoryId) => {
//
//     return await apiPython.post(`/training-search-by-photo`, {
//         category_id: categoryId,
//     }, { headers: { "Content-Type": "multipart/form-data" } });
// }

export const searchByPhoto = async (categoryId, imgBase64) => {

    return await apiPython.post(`/search-by-photo`, {
        category_id: categoryId,
        img_base64: imgBase64,
    }, { headers: { "Content-Type": "multipart/form-data" } });
}

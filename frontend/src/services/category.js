import { post } from '@/services/query';

const createCategory = async (card) => {
    return await post('admin/category/store', card);
}

// Сохраняем данные категории
const updateCategory = async (card) => {
    return await post('admin/category/update', card);
}

export default {
    createCategory,
    updateCategory,
};
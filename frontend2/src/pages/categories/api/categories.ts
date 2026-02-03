import { http } from '@/shared/api';

import type { Category, CategoryDTO } from '@/entities/category/model';

export const getCategoriesList = async (): Promise<Array<Category>> => {
  const list = await http.fetchGet<Array<CategoryDTO>>('categories');

  return list.data !== null ? list.data.map(item => categoryMapDTO(item)) : [];
}

const categoryMapDTO = (dto: CategoryDTO): Category => {
  return {
    id: dto.id,
    title: dto.title,
    desc: dto.description,
    preview_img: dto.url,
  };
}

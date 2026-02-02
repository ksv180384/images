import { http } from '@/shared/api';

import type { Category } from '@/pages/categories/model';

interface CategoryDTO {
  id: number,
  title: string,
  description: string | null,
  url: string
}

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

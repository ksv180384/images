import { http } from '@/shared/api';

interface CategoryDTO {
  id: number,
  title: string,
  description: string | null,
  url: string
}

export interface Category {
  id: number,
  title: string,
  desc: string | null,
  preview_img: string
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

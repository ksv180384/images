import { http } from '@/shared/api';

import type { Image, Tag } from '@/pages/images/model';

interface TagDTO {
  id: number,
  title: string
}

interface ImageDTO {
  id: number
  category_id: number
  image: string
  image_preview: string
  title: string | null
  description: string | null
  height: number
  width: number
  path_face: string | null
  path_full: string
  path_preview_full: string
  tags: TagDTO[] | null
  is_features: boolean
  image_created_at: string | null
  updated_at: string
  created_at: string
}

export const getImagesList = async (categoryId: number): Promise<Array<Image>> => {
  const list = await http.fetchGet<Array<ImageDTO>>(`category/${categoryId}/images`);

  return list.data !== null ? list.data.map(item => imageMapDTO(item)) : [];
}

const tagMapDTO = (dto: TagDTO): Tag => {
  return {
    id: dto.id,
    title: dto.title,
  };
}

const imageMapDTO = (dto: ImageDTO): Image => {

  const tagsList = dto.tags?.map((item: TagDTO) => {
    return tagMapDTO(item);
  }) ?? null;

  return {
    id: dto.id,
    category_id: dto.category_id,
    image: dto.image,
    image_preview: dto.image_preview,
    title: dto.title,
    description: dto.description,
    height: dto.height,
    width: dto.width,
    path_face: dto.path_face,
    path_full: dto.path_full,
    path_preview_full: dto.path_preview_full,
    tags: tagsList,
    is_features: dto.is_features,
    image_created_at: dto.image_created_at,
    updated_at: dto.updated_at,
    created_at: dto.created_at,
  };
}


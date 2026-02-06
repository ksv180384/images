import { http } from '@/shared/api';

import type { Image, ImageDTO, FilterParams } from '@/entities/image/model';
import type { ImageTag, ImageTagDTO } from '@/entities/image-tag/model';



export const getImagesList = async (categoryId: number, params?: FilterParams): Promise<Array<Image>> => {
  const searchParams = new URLSearchParams();

  if (params?.tags) {
    searchParams.set('tags', params.tags);
  }

  if (params?.range && params.range.length === 2) {
    const [from, to] = params.range as [string, string];
    if (from) {
      searchParams.set('range_from', from);
    }
    if (to) {
      searchParams.set('range_to', to);
    }
  }

  if (params?.no_date) {
    searchParams.set('no_date', '1');
  }

  const queryString = searchParams.toString();
  const url = queryString
    ? `category/${categoryId}/images?${queryString}`
    : `category/${categoryId}/images`;

  const list = await http.fetchGet<Array<ImageDTO>>(url);

  return list.data !== null ? list.data.map(item => imageMapDTO(item)) : [];
}

export const getImageTagsList = async (): Promise<Array<ImageTag>> => {
  const list = await http.fetchGet<Array<ImageTagDTO>>(`image-tags/all`);

  return list.data !== null ? list.data.map(item => imageMapDTO(item)) : [];
}

const imageTagMapDTO = (dto: ImageTagDTO): ImageTag => {
  return {
    id: dto.id,
    title: dto.title,
  };
}

const imageMapDTO = (dto: ImageDTO): Image => {

  const tagsList = dto.tags?.map((item: ImageTagDTO) => {
    return imageTagMapDTO(item);
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


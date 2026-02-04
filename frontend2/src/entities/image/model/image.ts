import type { Tag, TagDTO } from '@/entities/tag/model';

export interface Image {
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
  tags: Tag[] | null
  is_features: boolean
  image_created_at: string | null
  updated_at: string
  created_at: string
}

export interface ImageDTO {
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

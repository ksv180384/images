import { ITag } from '@/interfaces/ITag';

export interface IImage{
    id: number,
    category_id: number,
    image: string,
    image_preview: string,
    title: string,
    description: string,
    image_created_at: string,
    width: number,
    height: number,
    path_full: string,
    path_preview_full: string,
    path_face: string,
    is_features: boolean,
    created_at: string,
    updated_at: string,
    tags: ITag[],
}
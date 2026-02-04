export interface Category {
  id: number,
  title: string,
  desc: string | null,
  preview_img: string
}

export interface CategoryDTO {
  id: number,
  title: string,
  description: string | null,
  url: string
}

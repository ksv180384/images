import { http } from '@/shared/api';

export const getCategoriesList = async () => {
  const list = await http.fetchGet('categories');
}

import { http } from '@/shared/api';

import type { User } from '@/entities/user';

export const login = async (payload: {
  email: string,
  password: string,
}): Promise<User | null> => {

  const { data, status } = await http.fetchPost<User>('login', payload);

  return status === 200 && data !== null ? data : null;
}

export const logout = async (): Promise<User | null> => {
  const result = await http.fetchPost<User>('logout');
  return result.data;
}

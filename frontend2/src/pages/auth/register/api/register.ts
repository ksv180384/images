import { http } from '@/shared/api';

interface RegisterData {
  user: {
    role: string
  },
  token: string
}

export const register = async (payload: {
  email: string,
  password: string,
  password_confirm: string
}): Promise<RegisterData | null> => {

  const { data, status } = await http.fetchPost<RegisterData>('register', payload);

  return status === 204 && data !== null ? data : null;
}

import { http } from '@/shared/api';

interface LoginData {
  user: {
    role: string
  },
  token: string
}

export const login = async (payload: {
  email: string,
  password: string,
}): Promise<LoginData | null> => {

  const { data, status } = await http.fetchPost<LoginData>('login', payload);

  return status === 204 && data !== null ? data : null;
}

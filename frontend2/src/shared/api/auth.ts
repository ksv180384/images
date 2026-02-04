import { http } from '@/shared/api/http';
import type { User } from '@/entities/user/model';

interface CheckAuthData {
  authenticated: boolean,
  user: User
}

export const auth = {
  checkAuth: async (): Promise<CheckAuthData | null> => {
    const response = await http.fetchGet<CheckAuthData>('check-auth');
    return response.data; // response.data уже CheckAuthData | null
  },
};

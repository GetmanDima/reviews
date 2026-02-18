import { sendApiRequest } from '@/shared/lib/api';
import { ApiResponse } from '@/shared/types/api';

export const logout = (): Promise<ApiResponse> => {
    return sendApiRequest('/api/logout', 'POST');
};

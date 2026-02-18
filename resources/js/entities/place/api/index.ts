import { CreatePlaceFormData } from '@/features/place/CreatePlace/model/types';
import { sendApiRequest } from '@/shared/lib/api';
import { camelToSnakeKeys, parseUTCDateTimeString } from '@/shared/lib/helpers';
import { ApiResponse } from '@/shared/types/api';
import { PaginatedReviewsResponseData, Place } from '../model/types';

export const getPlaces = (): Promise<ApiResponse<Place[]>> => {
    return sendApiRequest('/api/places', 'GET');
};

export const getSinglePlace = (placeId: number): Promise<ApiResponse<Place>> => {
    return sendApiRequest(`/api/places/${placeId}`, 'GET');
};

export const createPlace = (data: CreatePlaceFormData): Promise<ApiResponse<Place>> => {
    return sendApiRequest('/api/places', 'POST', camelToSnakeKeys(data));
};

export const getReviews = async (placeId: number, page: number, perPage: number = 50): Promise<ApiResponse<PaginatedReviewsResponseData>> => {
    const response = await sendApiRequest(`/api/places/${placeId}/reviews?page=${page}&per_page=${perPage}`, 'GET');

    if (response.error) {
        const resultResponse = {
            ...response,
            transformedData: undefined,
        };

        return resultResponse;
    }

    const transformedData = response.transformedData;

    if (!transformedData) {
        throw new Error(`Unexpected empty api data when got events`);
    }

    const resultResponse = {
        ...response,
        transformedData: {
            ...transformedData,
            data: transformedData.data.map((review: any) => {
                return {
                    ...review,
                    publishedAt: review.publishedAt ? parseUTCDateTimeString(review.publishedAt) : null,
                };
            }),
        },
    };

    return resultResponse;
};

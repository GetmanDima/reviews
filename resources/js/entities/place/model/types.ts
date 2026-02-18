export interface Place {
    id: number;
    status: string;
    mapId: string;
    url: string;
    name: string | null;
    rating: number | null;
    reviewsCount: number | null;
    parsedReviewsCount: number | null;
}

export interface Review {
    id: number;
    image: string | null;
    name: string | null;
    rank: string | null;
    rating: number | null;
    text: string | null;
    publishedAt: Date | null;
}

export interface PaginatedReviewsResponseData {
    page: number;
    lastPage: number;
    total: number;
    data: Review[];
}

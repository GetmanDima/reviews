<template>
    <div>
        <div class="mb-4 flex items-center justify-between">
            <h3 class="text-h6">{{ t('review.title') }}</h3>

            <div class="flex items-center space-x-2">
                <v-btn icon variant="text" :disabled="disabledPrev" :title="t('review.previous')" @click="goToPrev">
                    <v-icon>mdi-chevron-left</v-icon>
                </v-btn>

                <v-btn icon variant="text" :disabled="disabledNext" :title="t('review.next')" @click="goToNext">
                    <v-icon>mdi-chevron-right</v-icon>
                </v-btn>
            </div>
        </div>

        <v-card v-if="loading && !reviews.length" class="mb-3">
            <v-card-text>
                <v-skeleton-loader type="list-item-avatar-three-line"></v-skeleton-loader>
            </v-card-text>
        </v-card>

        <v-card v-for="review in visibleReviews" :key="review.id" class="mb-3">
            <v-card-title>
                <div class="d-flex items-center gap-5">
                    <img v-if="review.image" :src="review.image" style="max-width: 40px; max-height: 40px" />
                    <div>
                        <div>{{ review.name || t('review.no_name') }}</div>
                        <v-chip v-if="review.rank" color="info">
                            {{ review.rank }}
                        </v-chip>
                    </div>
                </div>
            </v-card-title>
            <v-card-subtitle>
                <div class="flex items-center gap-3">
                    <div v-if="review.rating !== null" class="flex items-center">
                        <v-rating
                            :model-value="review.rating ?? 0"
                            density="compact"
                            half-increments
                            readonly
                            size="medium"
                            active-color="amber-accent-4"
                        ></v-rating>
                        <span class="ml-2">{{ review.rating }}</span>
                    </div>

                    <v-chip v-if="review.publishedAt">
                        {{ convertToDateTimeString(review.publishedAt) }}
                    </v-chip>
                </div>
            </v-card-subtitle>
            <v-card-text v-if="review.text">
                <p>
                    <span v-if="expandedReviews[review.id]">{{ review.text }}</span>
                    <span v-else>{{ truncateText(review.text, 260) }}</span>
                    <a v-if="review.text.length > 260" href="#" @click.prevent="toggleReview(review.id)" class="text-primary mt-1 block">
                        {{ expandedReviews[review.id] ? t('review.collapse') : t('review.expand') }}
                    </a>
                </p>
            </v-card-text>
        </v-card>

        <v-alert v-if="!loading && reviews.length === 0" type="info">
            {{ t('review.no_reviews') }}
        </v-alert>
    </div>
</template>

<script setup lang="ts">
import { getReviews as apiGetReviews } from '@/entities/place/api';
import { Review } from '@/entities/place/model/types';
import { useGlobalAlert } from '@/shared/composables/useGlobalAlert';
import { convertToDateTimeString } from '@/shared/lib/helpers';
import { computed, onMounted, reactive, ref } from 'vue';
import { useI18n } from 'vue-i18n';

const { t } = useI18n();
const { showErrorAlert } = useGlobalAlert();

const props = defineProps<{
    placeId: number;
    perPage: number;
}>();

const reviews = ref<Review[]>([]);
const loading = ref<boolean>(false);
const currentPage = ref<number>(1);
const lastPage = ref<number>(1);

const expandedReviews = reactive<{ [key: string]: boolean }>({});

const disabledNext = computed(() => loading.value || currentPage.value === lastPage.value);
const disabledPrev = computed(() => loading.value || currentPage.value === 1);

const truncateText = (text: string, length: number) => {
    if (text.length <= length) return text;
    return text.substring(0, length) + '...';
};

const toggleReview = (id: string | number) => {
    const reviewId = String(id);
    expandedReviews[reviewId] = !expandedReviews[reviewId];
};

const visibleReviews = computed(() => {
    return reviews.value.slice((currentPage.value - 1) * props.perPage, (currentPage.value - 1) * props.perPage + props.perPage);
});

const getReviews = async (placeId: number, page: number) => {
    if (!placeId) {
        return;
    }

    loading.value = true;

    const response = await apiGetReviews(placeId, page, props.perPage);

    if (response.error) {
        showErrorAlert(response.errorMessage ?? t('review.fetch_error'));
        loading.value = false;

        return;
    }

    const newReviews = response.transformedData?.data || [];
    lastPage.value = response.transformedData?.lastPage ?? 1;

    if (newReviews.length) {
        reviews.value = [...reviews.value, ...newReviews];
        currentPage.value = page;
    }

    loading.value = false;
};

const goToNext = async () => {
    if (props.placeId && currentPage.value < lastPage.value) {
        if ((currentPage.value - 1) * props.perPage + props.perPage >= reviews.value.length) {
            await getReviews(props.placeId, currentPage.value + 1);
        } else {
            currentPage.value++;
        }
    }
};

const goToPrev = async () => {
    if (currentPage.value > 1) {
        currentPage.value--;
    }
};

onMounted(() => {
    if (props.placeId) {
        getReviews(props.placeId, currentPage.value);
    }
});
</script>

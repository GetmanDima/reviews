<template>
    <v-card v-if="place" class="mb-3">
        <v-card-title>{{ place.name || t('place.show.card.no_name') }}</v-card-title>
        <v-card-subtitle>
            <div class="mb-3 flex items-center" v-if="place.rating !== null">
                <v-rating
                    :model-value="place.rating ?? 0"
                    density="compact"
                    half-increments
                    readonly
                    size="medium"
                    active-color="amber-accent-4"
                ></v-rating>
                <span class="ml-2">{{ place.rating }}</span>
            </div>
            <div v-if="place.url">
                <a :href="place.url" target="_blank" rel="noopener noreferrer">{{ place.url }}</a>
            </div>
        </v-card-subtitle>

        <v-card-text>
            <v-chip v-if="place.status" class="mr-2 mb-2"> {{ t('place.show.card.status') }}: {{ t('place.statuses.' + place.status) }} </v-chip>
            <v-chip v-if="place.status" class="mr-2 mb-2"> {{ t('place.show.card.reviews_count') }}: {{ place.reviewsCount ?? 0 }} </v-chip>
            <v-chip v-if="place.status" class="mr-2 mb-2">
                {{ t('place.show.card.parsed_reviews_count') }}: {{ place.parsedReviewsCount ?? 0 }}
            </v-chip>
        </v-card-text>
    </v-card>

    <v-alert v-else-if="!loading" type="error">
        {{ t('place.show.card.not_found') }}
    </v-alert>

    <v-card v-else-if="loading" class="mb-3">
        <v-card-text>
            <v-skeleton-loader type="article"></v-skeleton-loader>
        </v-card-text>
    </v-card>
</template>

<script setup lang="ts">
import { getSinglePlace as apiGetSinglePlace } from '@/entities/place/api';
import { Place } from '@/entities/place/model/types';
import { useGlobalAlert } from '@/shared/composables/useGlobalAlert';
import { onMounted, ref } from 'vue';
import { useI18n } from 'vue-i18n';

const { t } = useI18n();
const { showErrorAlert } = useGlobalAlert();

const props = defineProps<{
    placeId?: number;
}>();

const place = ref<Place | null>(null);
const loading = ref<boolean>(true);

const getPlace = async (placeId: number) => {
    if (!placeId) return;

    loading.value = true;
    place.value = null;

    const placeResponse = await apiGetSinglePlace(placeId);

    if (placeResponse.error) {
        showErrorAlert(placeResponse.errorMessage ?? t('place.show.card.fetch_error'));
    } else if (placeResponse.transformedData) {
        place.value = placeResponse.transformedData;
    }

    loading.value = false;
};

onMounted(() => {
    if (props.placeId) {
        getPlace(props.placeId);
    }
});
</script>

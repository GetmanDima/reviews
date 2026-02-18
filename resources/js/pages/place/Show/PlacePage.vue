<template>
    <inertia-head :title="t('place.show.title')" />
    <div class="w-full px-5" style="max-width: 1500px; margin-top: 80px">
        <content-block :max-width="'100%'">
            <v-alert v-if="showProcessingAlert" type="success" class="mb-6" closable @click:close="closeAlert">
                {{ t('place.show.processing_queued') }}
            </v-alert>

            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                <div>
                    <place-reviews v-if="selectedPlaceId" :place-id="selectedPlaceId" :per-page="50" />
                </div>

                <div>
                    <h3 class="text-h6 mt-2 mb-6">{{ t('place.show.card.title') }}</h3>
                    <place-card v-if="selectedPlaceId" :place-id="selectedPlaceId" />
                </div>
            </div>
        </content-block>
    </div>
</template>

<script setup lang="ts">
import { usePlace } from '@/entities/place/composables/usePlace';
import PlaceCard from '@/features/place/PlaceCard';
import PlaceReviews from '@/features/place/Reviews';
import AuthenticatedLayout from '@/layouts/AuthenticatedLayout';
import { onMounted, ref } from 'vue';
import { useI18n } from 'vue-i18n';

const { t } = useI18n();

defineOptions({
    layout: AuthenticatedLayout,
});

const { selectPlace, selectedPlaceId } = usePlace();

const showProcessingAlert = ref(false);

const getPlaceId = () => {
    const path = window.location.pathname;
    const match = path.match(/\/places\/(\d+)(?=\?|$)/);
    return match ? parseInt(match[1]) : null;
};

const checkCreatedParam = () => {
    setTimeout(() => {
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.get('created')) {
            showProcessingAlert.value = true;
            const newUrl = window.location.pathname + window.location.hash;
            window.history.replaceState({}, '', newUrl);
        }
    }, 100);
};

const closeAlert = () => {
    showProcessingAlert.value = false;
};

onMounted(() => {
    const placeId = getPlaceId();

    if (placeId) {
        selectPlace(placeId);
    }

    checkCreatedParam();
});
</script>

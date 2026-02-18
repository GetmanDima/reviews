<template>
    <div class="place-list-container">
        <h2 class="text-h6 text-light-blue-darken-2 ml-4">{{ t('place.index.title') }}:</h2>
        <v-list nav>
            <v-list-item
                v-for="place in places"
                :key="place.id"
                :to="`/places/${place.id}`"
                :active="props.selectedPlaceId === place.id"
                @click="() => redirect('/places/' + place.id)"
            >
                <v-list-item-title class="text-subtitle-1 font-weight-medium">
                    {{ place.mapId }}
                </v-list-item-title>
            </v-list-item>

            <v-list-item v-if="places.length === 0 && !loading">
                <v-list-item-title class="text-subtitle-1 font-weight-medium">
                    {{ t('place.index.no_data') }}
                </v-list-item-title>
            </v-list-item>
        </v-list>
    </div>
</template>

<script setup lang="ts">
import { getPlaces as apiGetPlaces } from '@/entities/place/api';
import { Place } from '@/entities/place/model/types';
import { useGlobalAlert } from '@/shared/composables/useGlobalAlert';
import { redirect } from '@/shared/lib/helpers';
import { onMounted, ref } from 'vue';
import { useI18n } from 'vue-i18n';

const { t } = useI18n();
const { showErrorAlert } = useGlobalAlert();

const places = ref<Place[]>([]);
const loading = ref<boolean>(true);

const props = defineProps<{
    selectedPlaceId: number | null;
}>();

onMounted(async () => {
    const response = await apiGetPlaces();
    loading.value = false;

    if (response.error) {
        showErrorAlert(response.errorMessage ?? t('place.index.fetch_error'));
    } else {
        places.value = response.transformedData || [];
    }
});
</script>

<style scoped>
.place-list-container {
    max-width: 100%;
}
</style>

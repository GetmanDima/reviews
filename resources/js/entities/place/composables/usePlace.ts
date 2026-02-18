import { inject, provide, ref, Ref } from 'vue';

export const usePlace = () => {
    let selectedPlaceId = inject('selectedPlaceId') as Ref<number | null> | undefined;

    if (!selectedPlaceId) {
        selectedPlaceId = ref<number | null>(null);
        provide<typeof selectedPlaceId>('selectedPlaceId', selectedPlaceId);
    }

    const selectPlace = (placeId: number) => {
        selectedPlaceId.value = placeId;
    };

    return {
        selectedPlaceId,
        selectPlace,
    };
};

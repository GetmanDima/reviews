<template>
    <v-form fast-fail @submit.prevent="submit" class="pb-3">
        <div class="text-caption mb-3">{{ t('place.create.url_example') + ':' }} https://yandex.ru/maps/org/pyatyorochka/194764071445/reviews/</div>
        <v-text-field
            v-model="form.url"
            :label="t('place.create.fields.url') + ' *'"
            :rules="[validationRules.required, validationRules.yandexMapUrl]"
            :error-messages="form.errors.url"
            class="mb-3"
            persistent-placeholder
        ></v-text-field>

        <v-btn :loading="submitLoading" :disabled="submitLoading" color="primary" type="submit" block>{{ t('place.create.submit') }}</v-btn>
    </v-form>
</template>

<script setup lang="ts">
import { createPlace as apiCreatePlace } from '@/entities/place/api';
import { useForm } from '@/shared/composables/useForm';
import { useGlobalAlert } from '@/shared/composables/useGlobalAlert';
import { redirect } from '@/shared/lib/helpers';
import validationRules from '@/shared/lib/validationRules';
import { ApiResponse } from '@/shared/types/api';
import { ref } from 'vue';
import { useI18n } from 'vue-i18n';
import { SubmitEventPromise } from 'vuetify';
import { CreatePlaceFormData } from '../model/types';

const { t } = useI18n();
const { showErrorAlert } = useGlobalAlert();

const { form, validateForm, setErrorsFromApiResponse } = useForm<CreatePlaceFormData>({
    url: '',
});

const submitLoading = ref(false);

const submit = async (event: SubmitEventPromise) => {
    submitLoading.value = true;

    const isValidForm = await validateForm(event);

    if (!isValidForm) {
        submitLoading.value = false;
        return;
    }

    const response = await apiCreatePlace(form.data());

    if (response.error) {
        submitLoading.value = false;
        handleApiError(response);
    } else {
        const placeId = response.transformedData?.id;

        if (placeId) {
            redirect(`/places/${placeId}?created=true`);
        }
    }
};

const handleApiError = async (response: ApiResponse) => {
    if (response.status === 429) {
        showErrorAlert(t('place.create.errors.too_many_requests'));
    } else if (response.hasValidationErrors) {
        setErrorsFromApiResponse(response);
    } else {
        showErrorAlert(response.errorMessage ?? t('error.unknown'));
    }
};
</script>

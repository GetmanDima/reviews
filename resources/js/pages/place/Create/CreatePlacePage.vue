<template>
    <inertia-head :title="t('place.create.title')" />
    <content-block class="w-full px-5" style="max-width: 1500px; margin-top: 80px">
        <h3 class="text-h6 mt-2 mb-6">{{ t('place.create.title') }}</h3>
        <create-place />
    </content-block>
</template>

<script setup lang="ts">
import CreatePlace from '@/features/place/CreatePlace';
import AuthenticatedLayout from '@/layouts/AuthenticatedLayout';
import ContentBlock from '@/shared/ui/ContentBlock';
import { Head as InertiaHead } from '@inertiajs/vue3';
import { useI18n } from 'vue-i18n';

const { t } = useI18n();

defineOptions({
    layout: AuthenticatedLayout,
});
</script>

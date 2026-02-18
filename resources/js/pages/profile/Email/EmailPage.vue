<template>
    <inertia-head :title="t('email.title')" />
    <content-block class="w-full px-5" style="max-width: 1500px; margin-top: 80px">
        <v-tabs v-model="tab" color="primary" class="mb-8">
            <v-tab value="personal-data">{{ t('personal_data.title') }}</v-tab>
            <v-tab value="email">{{ t('email.title') }}</v-tab>
        </v-tabs>

        <email-update-form class="mb-2" />
    </content-block>
</template>

<script setup lang="ts">
import EmailUpdateForm from '@/features/profile/EmailUpdateForm';
import AuthenticatedLayout from '@/layouts/AuthenticatedLayout';
import { useNavigation } from '@/shared/composables/useNavigation';
import { redirect } from '@/shared/lib/helpers';
import { getDefaultNavigationItems } from '@/shared/lib/navigation';
import ContentBlock from '@/shared/ui/ContentBlock';
import { Head as InertiaHead } from '@inertiajs/vue3';
import { onMounted, ref, watch } from 'vue';
import { useI18n } from 'vue-i18n';

const { t } = useI18n();

const { selectNavigationItem } = useNavigation();

defineOptions({
    layout: AuthenticatedLayout,
});

const tab = ref('email');

watch(tab, (newTab) => {
    if (newTab !== 'email') {
        redirect('/profile/' + newTab);
    }
});

onMounted(() => {
    selectNavigationItem(getDefaultNavigationItems(t)['profile']);
});
</script>

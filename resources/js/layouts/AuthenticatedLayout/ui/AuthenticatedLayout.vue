<template>
    <app-layout>
        <!-- Desktop Navigation Drawer -->
        <v-navigation-drawer v-model="drawer" :temporary="$vuetify.display.mobile" :permanent="!$vuetify.display.mobile" app class="pa-0">
            <div class="mt-2 flex items-center">
                <a href="/" class="flex items-center">
                    <v-icon icon="mdi-calendar-check" :size="30" class="ml-2"></v-icon>
                    <h1 class="text-h6 ml-3">Reviews</h1>
                </a>
                <v-btn icon variant="flat" class="ml-auto" @click="drawer = false">
                    <v-icon>mdi-close</v-icon>
                </v-btn>
            </div>

            <navigation :items="navigationItems" :selectedItem="selectedNavigationItem" class="mt-3" @select-item="selectNavigationItem" />

            <v-divider class="mb-5"></v-divider>
            <place-list :selectedPlaceId="selectedPlaceId" />
        </v-navigation-drawer>

        <!-- App Bar with Hamburger Button -->
        <v-app-bar :elevation="2">
            <template v-slot:prepend>
                <v-app-bar-nav-icon v-if="!drawer" @click="drawer = true" class="mr-2"></v-app-bar-nav-icon>
            </template>

            <template v-slot:append>
                <div class="hidden sm:block">
                    <v-btn @click="() => redirect('/profile/personal-data')">
                        <v-icon icon="mdi-account" :size="25" class="me-1"></v-icon>
                        <span class="normal-case">{{ userFullName }}</span>
                    </v-btn>
                </div>
                <div class="pt-6">
                    <select-language variant="undefined" label="" width="90" />
                </div>
                <div>
                    <logout-button />
                </div>
            </template>
        </v-app-bar>

        <v-main class="pt-0">
            <div>
                <slot />
            </div>
        </v-main>
    </app-layout>
</template>

<script setup lang="ts">
import { usePlace } from '@/entities/place/composables/usePlace';
import { useAuthUser } from '@/entities/user/composables/useAuthUser';
import LogoutButton from '@/features/auth/LogoutButton';
import SelectLanguage from '@/features/layout/SelectLanguage';
import PlaceList from '@/features/place/PlaceList';
import AppLayout from '@/layouts/AppLayout';
import { useNavigation } from '@/shared/composables/useNavigation';
import { redirect } from '@/shared/lib/helpers';
import { getDefaultNavigationItems } from '@/shared/lib/navigation';
import { computed, onMounted, ref } from 'vue';
import { useI18n } from 'vue-i18n';
import Navigation from './Navigation.vue';

const { t } = useI18n();

const { user, updateUser } = useAuthUser();
const { selectedNavigationItem, selectNavigationItem } = useNavigation();
const { selectedPlaceId } = usePlace();

const navigationItems = computed(() => getDefaultNavigationItems(t));
const drawer = ref(true);

onMounted(() => {
    updateUser();
});

const userFullName = computed(() => {
    if (!user.value) {
        return '';
    }

    const lastName = user.value.lastName;
    const firstName = user.value.firstName;
    const middleName = user.value.middleName;

    if (lastName && firstName && middleName) {
        return `${lastName} ${firstName} ${middleName}`;
    }

    if (lastName && firstName) {
        return `${lastName} ${firstName}`;
    }

    return firstName;
});
</script>

<template>
    <v-list nav>
        <v-list-item
            v-for="item in props.items"
            :key="item.key"
            :to="item.link"
            :active="props.selectedItem?.key === item.key"
            class="mb-2 py-4"
            @click="() => onNavigationItemClick(item)"
        >
            <v-list-item-title class="text-subtitle-1 font-weight-medium">
                {{ item.title }}
            </v-list-item-title>
        </v-list-item>
    </v-list>
</template>

<script setup lang="ts">
import { redirect } from '@/shared/lib/helpers';
import { NavigationItem, NavigationItems } from '@/shared/types/navigation';
import { onMounted } from 'vue';

const props = withDefaults(defineProps<{ items: NavigationItems; selectedItem: NavigationItem | null; vertical?: boolean }>(), {
    vertical: false,
});
const emit = defineEmits(['selectItem']);

onMounted(() => {
    const navigationItemForUrl = Object.values(props.items).find((item) => item.link === window.location.pathname);

    if (navigationItemForUrl) {
        emit('selectItem', navigationItemForUrl);
    }
});

const onNavigationItemClick = (navigationItem: NavigationItem) => {
    emit('selectItem', navigationItem);
    redirect(navigationItem.link);
};
</script>

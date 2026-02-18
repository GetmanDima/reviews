import { NavigationItems } from '../types/navigation';

export const getDefaultNavigationItems = (t: (key: string) => string): NavigationItems => {
    return {
        createPlace: { key: 'createPlace', title: t('navigation.default.create_place'), link: '/places/create' },
        profile: { key: 'profile', title: t('navigation.default.profile'), link: '/profile/personal-data' },
    };
};

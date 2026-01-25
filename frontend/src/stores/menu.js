import { defineStore } from 'pinia';

export const useMenuStore = defineStore('menuStore', {
    state: () => ({
        menu: [
            { path: 'admin/home', path_name: 'home', title: 'Главная', for_auth: false },
            { path: 'admin/categories', path_name: 'admin.categories', title: 'Категории', for_auth: true },
            { path: 'admin/tags', path_name: 'admin.tags', title: 'Теги', for_auth: true },
        ],
    })
});
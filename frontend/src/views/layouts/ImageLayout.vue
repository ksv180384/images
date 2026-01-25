<template>

    <div class="container">
        <header class="blog-header py-3">
            <div class="d-flex flex-nowrap justify-content-between align-items-center">
                <router-link
                  :to="{ name: 'home' }"
                  class="header-logo text-dark text-center text-decoration-none"
                >
                  Imagines
                </router-link>
                <div>
                    <nav class="nav d-flex justify-content-start">
                        <router-link
                            :to="{ name: 'admin.category.edit', params: { id: categoryId } }"
                            class="p-2 link-secondary"
                        >
                            Назад
                        </router-link>
                    </nav>
                </div>
            </div>
        </header>
    </div>

    <slot/>

</template>

<script setup>

import { useRoute } from 'vue-router';

const route = useRoute();
const categoryId =  route.params.category_id;

</script>

<style scoped>

</style>
<script setup>
import { Link } from "@inertiajs/vue3";

defineProps({
    links: Array,
});
</script>

<template>
    <div class="flex justify-center mt-4 space-x-4">
        <template v-for="(link, key) in links" :key="key">
            <div
                v-if="link.url === null"
                v-html="link.label"
                class="mb-1 mr-1 px-4 py-3 text-gray-400 text-sm leading-4 border rounded">
            </div>
            <Link
                v-else
                :href="link.url"
                v-html="link.label"
                class="mb-1 mr-1 px-4 py-3 text-gray-400 text-sm leading-4 bg-white rounded hover:bg-gray-200 focus:text-gray-900 focus:border-indigo-500"
                :class="{ 'bg-indigo-300 text-gray-500 ': link.active }"
            ></Link>
        </template>
    </div>
</template>

<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import { Head, Link } from "@inertiajs/vue3";
import { usePermission } from "@/composables/permissions";

const props = defineProps({
    post: {
        type: Object,
        required: true,
    },
});
const { hasRoles } = usePermission();

</script>

<template>

    <Head title="Actualizar Faqs" />

    <AdminLayout>
        <div class="fullscreen mx-auto py-4">
            <div class="flex justify-between">
                <Link :href="route('posts.index')"
                    class="px-3 py-2 text-white font-semibold bg-gray-500 hover:bg-gray-600 rounded">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="w-5 h-5 inline-block align-text-top mr-1">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="m11.25 9-3 3m0 0 3 3m-3-3h7.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                </svg>
                Regresar</Link>
            </div>

            <div class="mt-6 fullscreen mx-auto bg-slate-100 shadow-lg rounded-lg p-6">
                <div class="flex text-xs italic">
                    Creado
                    <span class="font-semibold mx-1">
                        {{ post.created_at }}</span>
                </div>
                <div class="flex text-xs italic">
                    Ultima actualizacion
                    <span class="font-semibold mx-1">
                        {{ post.updated_at }}</span>
                </div>
                <template v-if="hasRoles(['admin', 'moderador', 'escritor'])">
                    <div class="flex mt-3 text-xs">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="w-4 h-4">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18M9 6.75h1.5m-1.5 3h1.5m-1.5 3h1.5m3-6H15m-1.5 3H15m-1.5 3H15M9 21v-3.375c0-.621.504-1.125 1.125-1.125h3.75c.621 0 1.125.504 1.125 1.125V21" />
                        </svg>
                        <span class="font-semibold mx-1">
                            {{ post.department }}</span>
                    </div>
                </template>
                <h5 class="mb-2 text-4xl font-bold tracking-tight text-gray-700 hover:text-indigo-800">
                    {{ post.title }}
                </h5>

                <div class="flex">
                    <span
                        class="inline-flex items-center py-2 px-3 text-sm font-medium text-center text-white bg-slate-700 rounded-lg hover:bg-slate-800 focus:ring-4 focus:outline-none focus:ring-blue-300">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="#9aa3ae" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="w-5 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M9.568 3H5.25A2.25 2.25 0 0 0 3 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 0 0 5.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 0 0 9.568 3Z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6Z" />
                        </svg>
                        {{ post.category }}</span>

                    <span
                        class="inline-flex items-center py-2 px-3 text-sm font-medium text-center text-white bg-slate-700 rounded-lg hover:bg-slate-800 focus:ring-4 focus:outline-none focus:ring-blue-300 mx-1">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="w-5 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M9.568 3H5.25A2.25 2.25 0 0 0 3 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 0 0 5.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 0 0 9.568 3Z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6Z" />
                        </svg>
                        {{ post.subcategory }}</span>
                </div>

                <div class="mt-10 mb-3 font-normal text-gray-700 dark:text-gray-400" v-html="post.description">
                </div>

                <!-- <div class="flex mt-10">
                    <p class="mb-3 font-normal text-gray-700 dark:text-gray-400 ">
                        {{ post.description }}
                    </p>
                </div> -->


            </div>
        </div>
    </AdminLayout>
</template>
<style>
.fullscreen {
  min-width: 80vw; /* 100% del ancho del viewport */
}
</style>

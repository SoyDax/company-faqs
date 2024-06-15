<script setup>
import { ref, watch } from "vue";
import { Head, Link, useForm } from "@inertiajs/vue3";
import { router } from "@inertiajs/vue3";
import AdminLayout from "@/Layouts/AdminLayout.vue";
import Pagination from "@/Components/Pagination.vue";
import Swal from "sweetalert2";
import Table from "@/Components/Table.vue";
import TableDataCell from "@/Components/TableDataCell.vue";
import TableHeaderCell from "@/Components/TableHeaderCell.vue";
import TableRow from "@/Components/TableRow.vue";
import { usePermission } from "@/composables/permissions";

//  defineProps({
//    posts: Object,
//  });

// defineProps(["posts"]);
const props = defineProps({
    posts: Object,
    categories: Object,
    filters: Object,
    from: Number, //propiedad from de la paginacion de laravel, representa el numero del primer elemento de lla pagina actual
});
const showSelect = ref(false);
// Buscador de registros, filters mantiene busqueda al cambiar pagina
const search = ref(props.filters.search);
// Añade una nueva propiedad ref para la categoría seleccionada
const selectedCategory = ref(props.filters.category_id);
const perPage = ref(10);
// const open = ref(false);
// instancia router
// Añade un observador para la categoría seleccionada
watch(selectedCategory, (value) => {
    router.get(
        "/posts",
        { search: search.value, perPage: perPage.value, category_id: value },
        {
            preserveState: true,
            replace: true,
        }
    );
});

watch(search, (value) => {
    router.get(
        "/posts",
        { search: value, perPage: perPage.value },
        {
            preserveState: true,
            replace: true,
        }
    );
});

function getPosts() {
    router.get(
        "/posts",
        {
            perPage: perPage.value,
            search: search.value,
            category_id: selectedCategory.value,
        },
        {
            preserveState: true,
            replace: true,
        }
    );
}

const form = useForm({});
// const { hasPermission } = usePermission();
const { hasRole, hasRoles } = usePermission();
const confirmDeletePost = (id) => {
    Swal.fire({
        title: "Eliminar Faqs",
        text: "¿Esta seguro que desea eliminar esta publicacion?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Si, eliminar",
        cancelButtonText: "No",
    }).then((result) => {
        if (result.isConfirmed) {
            form.delete(route("posts.destroy", id), {
                onSuccess: () => {
                    Swal.fire("Eliminado correctamente", "", "success");
                },
                onError: () => {
                    Swal.fire(
                        "Error",
                        "Sucedio un error al eliminar la publicacion",
                        "error"
                    );
                },
            });
        }
    });
};
</script>

<template>

    <Head title="Posts" />

    <AdminLayout>
        <div class="fullscreen mx-auto py-4">
            <div class="flex justify-between">
                <!-- <template v-if="hasPermission('Crear FAQ')">  -->

                <Link :href="route('posts.create')"
                    class="px-3 py-2 text-white font-semibold bg-green-500 hover:bg-green-600 rounded">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                    class="h-5 w-5 inline-block align-text-top mr-1">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 9v6m3-3H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                </svg>
                CREAR
                </Link>
                <!-- </template> -->
               
            </div>

            <div class="mt-6">
                <!-- Buscador y dropdown -->
                <div class="w-full mb-3 overflow-hidden dark:bg-gray-700 rounded-lg">
                    <div class="p-2 m-2">
                        <div class="flex justify-between">
                            <div class="flex-1">
                                <div class="relative">
                                    <div class="absolute flex items-center ml-2 h-full">
                                        <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true"
                                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                                stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                                        </svg>
                                    </div>

                                    <input v-model="search" type="text" placeholder="Buscar registro"
                                        class="px-8 py-3 w-full md:w-2/6 rounded-md bg-gray-200 border-transparent focus:border-gray-500 focus:bg-white focus:ring-0 focus:border-gray-200" />
                                </div>
                            </div>

                            <div class="flex mr-4 cursor-pointer">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="#e5e7eb" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="#e5e7eb" class="w-16 h-12" @click="showSelect = !showSelect">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M12 3c2.755 0 5.455.232 8.083.678.533.09.917.556.917 1.096v1.044a2.25 2.25 0 0 1-.659 1.591l-5.432 5.432a2.25 2.25 0 0 0-.659 1.591v2.927a2.25 2.25 0 0 1-1.244 2.013L9.75 21v-6.568a2.25 2.25 0 0 0-.659-1.591L3.659 7.409A2.25 2.25 0 0 1 3 5.818V4.774c0-.54.384-1.006.917-1.096A48.32 48.32 0 0 1 12 3Z" />
                                </svg>
   
                                <select v-model="selectedCategory" v-show="showSelect"
                                    class="px-4 pr-8 py-3 w-full rounded-md bg-gray-200 border-transparent focus:border-gray-500 hover:bg-gray-300 focus:bg-white focus:ring-0 text-gray-500 focus:border-gray-200 transition-all duration-500 ease-in-out font-bold">
                                    <option value="">
                                        Todas las categorías
                                    </option>
                                    <option v-for="category in categories" :value="category.id">
                                        {{ category.name }}
                                    </option>
                                </select>
                            </div>
                            <div class="flex">
                                <select v-model="perPage" @change="getPosts"
                                    class="px-4 pr-8 py-3 w-full rounded-md bg-gray-200 border-transparent focus:border-gray-500 hover:bg-gray-300 focus:bg-white focus:ring-0 text-gray-500 focus:border-gray-200 transition-all duration-500 ease-in-out">
                                    <option value="5">Mostrar 5</option>
                                    <option value="10">Mostrar 10</option>
                                    <option value="15">Mostrar 15</option>
                                    <option value="30">Mostrar 30</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Tabla de faqs -->
                <Table>
                    <template #header>
                        <TableRow>
                            <TableHeaderCell>#</TableHeaderCell>
                            <TableHeaderCell>Titulo</TableHeaderCell>
                            <TableHeaderCell>Categoria</TableHeaderCell>
                            <TableHeaderCell>SubCategoria</TableHeaderCell>
                            <template v-if="hasRoles(['admin', 'moderador', 'escritor'])">
                                <TableHeaderCell>Departamento</TableHeaderCell>
                            </template>
                            <TableHeaderCell>Descripcion</TableHeaderCell>
                            <TableHeaderCell class="flex justify-end mr-10"> Acciones</TableHeaderCell>

                        </TableRow>
                    </template>
                    <template #default>
                        <TableRow v-for="(post, i) in posts.data" :key="post.id" class="border-b">
                            <TableDataCell>{{ from + i }}</TableDataCell>
                            <TableDataCell>
                                <Link :href="route('posts.show', post.id)" class="text-gray-500 hover:text-gray-800">
                                    {{ post.title }}
                                </Link>
                            </TableDataCell>
                            <TableDataCell>{{ post.category }}</TableDataCell>
                            <TableDataCell>{{
                                post.subcategory
                                }}</TableDataCell>
                            <template v-if="
                                hasRoles(['admin', 'moderador', 'escritor'])
                            ">
                                <TableDataCell>{{
                                    post.department
                                    }}</TableDataCell>
                            </template>
                            <!-- FUncion slice para limitar la cantidad de caracteres a mostrar, funciona añade ... si el texto es mayor a 50 caracteres -->
                         <TableDataCell v-html="post.description.slice(0, 40) + (post.description.length > 50 ? '...' : '')">
                        </TableDataCell>

                            <TableDataCell class="flex justify-end space-x-2">
                                <!-- <template v-if="hasPermission('Editar FAQ')">  -->

                                <Link :href="route('posts.show', post.id)" as="button"
                                    class="px-2 py-2 text-white font-semibold bg-gray-400 hover:bg-gray-500 rounded">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                </svg>

                                </Link>
                                <template v-if="hasRoles(['admin', 'moderador', 'escritor'])">
                                    <Link :href="route('posts.edit', post.id)" as="button"
                                        class="px-2 py-2 text-white font-semibold bg-blue-500 hover:bg-blue-600 rounded">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                    </svg>
                                    </Link>
                                    <!-- </template> -->
                                    <!-- 
                                     <template v-if="hasPermission('Eliminar FAQ')">  -->
                                    <button @click.prevent="
                                        confirmDeletePost(post.id)
                                        "
                                        class="px-2 py-2 text-white font-semibold bg-red-500 hover:bg-red-600 rounded">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                        </svg>
                                    </button>
                                </template>
                                <!-- </template> -->
                            </TableDataCell>

                        </TableRow>
                    </template>
                </Table>
                <div class="m-2 p-2">
                    <Pagination :links="posts.links" />
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
<style>
.fullscreen {
  min-width: 80vw; /* 100% del ancho del viewport */
}
</style>
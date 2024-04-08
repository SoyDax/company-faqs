<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';

import { Head, Link, useForm } from "@inertiajs/vue3";
import { usePermission } from '@/composables/permissions';
import { ref } from "vue";
import Table from '@/Components/Table.vue';
import TableRow from '@/Components/TableRow.vue';
import TableHeaderCell from '@/Components/TableHeaderCell.vue';
import TableDataCell from '@/Components/TableDataCell.vue';
import Swal from 'sweetalert2';

defineProps(['posts']);
const form = useForm({})

const { hasPermission } = usePermission();

const confirmDeletePost = (id) => {
  Swal.fire({
    title: 'Eliminar Faqs',
    text: '¿Esta seguro que desea eliminar esta publicacion?',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonText: 'Si, eliminar',
    cancelButtonText: 'No',
  }).then((result) => {
    if (result.isConfirmed) {
      form.delete(route('posts.destroy', id), {
        onSuccess: () => {
          Swal.fire('Eliminado correctamente', '', 'success');
        },
        onError: () => {
          Swal.fire('Error', 'Sucedio un error al eliminar la publicacion', 'error');
        }
      });
    }
  });
};

</script>

<template>
<Head title="Posts" />

<AdminLayout>
    <div class="max-w-7xl mx-auto py-4">
        <div class="flex justify-between">
          <!-- <template v-if="hasPermission('Crear FAQ')">  -->
     
            <Link :href="route('posts.create')" class="px-3 py-2 text-white font-semibold bg-green-500 hover:bg-green-600 rounded">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="h-5 w-5 inline-block align-text-top mr-1">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v6m3-3H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
            </svg>
            CREAR
            </Link>
<!-- 
          </template> -->
            <h1>Posts Index Page</h1>
        </div>
        <div class="mt-6">
            <Table>
                <template #header>
                    <TableRow>
                        <TableHeaderCell>ID</TableHeaderCell>
                        <TableHeaderCell>Titulo</TableHeaderCell>
                        <TableHeaderCell>Accion</TableHeaderCell>
                    </TableRow>
                </template>
                <template #default>
                    <TableRow v-for="post in posts" :key="post.id" class="border-b">
                        <TableDataCell>{{ post.id }}</TableDataCell>
                        <TableDataCell>{{ post.title }}</TableDataCell>
                        <TableDataCell class="space-x-4">
                          <!-- <template v-if="hasPermission('Editar FAQ')">  -->
                            <Link :href="route('posts.edit', post.id)" as="button" class="px-3 py-2 text-white font-semibold bg-blue-500 hover:bg-blue-600 rounded">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                            </svg>
                            </Link>
                          <!-- </template> -->
<!-- 
                          <template v-if="hasPermission('Eliminar FAQ')">  -->
                            <button @click.prevent="confirmDeletePost(post.id)" class="px-2 py-2 text-white font-semibold bg-red-500 hover:bg-red-600 rounded">
                              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                            </svg>
                            </button>
                          <!-- </template> -->
                            
                        </TableDataCell>

                    </TableRow>
                </template>
            </Table>
        </div>
    </div>
</AdminLayout>
</template>

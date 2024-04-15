<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";

import { Head, Link, useForm } from "@inertiajs/vue3";
import { ref, watch, defineProps } from "vue";
import { router } from "@inertiajs/vue3";
import Table from "@/Components/Table.vue";
import TableRow from "@/Components/TableRow.vue";
import TableHeaderCell from "@/Components/TableHeaderCell.vue";
import TableDataCell from "@/Components/TableDataCell.vue";
import Pagination from "@/Components/Pagination.vue";
import Swal from "sweetalert2";

// defineProps(['users']);
const props = defineProps({
  users: Object,
  filters: Object,
  from: Number, //propiedad from de la paginacion de laravel, representa el numero del primer elemento de lla pagina actual
});
// Buscador de registros, filters mantiene busqueda al cambiar pagina
const search = ref(props.filters.search);
const perPage = ref(5);

// instancia router
watch(search, (value) => {
  router.get(
    "/admin/users",
    { search: value, perPage: perPage.value },
    {
      preserveState: true,
      replace: true,
    }
  );
});

function getUsers() {
  router.get(
    "/admin/users",
    { perPage: perPage.value, search: search.value },
    {
      preserveState: true,
      replace: true,
    }
  );
}

const form = useForm({});

const confirmDeleteuser = (id) => {
  Swal.fire({
    title: "Eliminar usuario",
    text: "¿Esta seguro que desea eliminar este usuario?",
    icon: "warning",
    showCancelButton: true,
    confirmButtonText: "Si, eliminar",
    cancelButtonText: "No",
  }).then((result) => {
    if (result.isConfirmed) {
      form.delete(route("users.destroy", id), {
        onSuccess: () => {
          Swal.fire("Eliminado correctamente", "", "success");
        },
        onError: () => {
          Swal.fire(
            "Error",
            "Sucedio un error al eliminar el usuario",
            "error"
          );
        },
      });
    }
  });
};
</script>

<template>

  <Head title="Users index" />

  <AdminLayout>
    <div class="max-w-7xl mx-auto py-4">
      <div class="flex justify-between">
        <Link :href="route('users.create')"
          class="px-3 py-2 text-white font-semibold bg-green-500 hover:bg-green-600 rounded">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"
          class="h-5 w-5 inline-block align-text-top mr-1">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M12 9v6m3-3H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
        </svg>
        CREAR
        </Link>
        <h1>User Index Page</h1>
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
                      <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                    </svg>
                  </div>

                  <input v-model="search" type="text" placeholder="Buscar registro"
                    class="px-8 py-3 w-full md:w-2/6 rounded-md bg-gray-200 border-transparent focus:border-gray-500 focus:bg-white focus:ring-0 focus:border-gray-200" />
                </div>
              </div>
              <div class="flex">
                <select v-model="perPage" @change="getUsers"
                  class="px-4 pr-8 py-3 w-full rounded-md bg-gray-200 border-transparent focus:border-gray-500 focus:bg-white focus:ring-0 text-gray-500 focus:border-gray-200">
                  <option value="5">Mostrar 5</option>
                  <option value="10">Mostrar 10</option>
                  <option value="15">Mostrar 15</option>
                </select>
              </div>
            </div>
          </div>
        </div>
        <!-- Tabla de usuarios -->
        <Table>
          <template #header>
            <TableRow>
              <TableHeaderCell>#</TableHeaderCell>
              <TableHeaderCell>Nombre</TableHeaderCell>
              <TableHeaderCell>Email</TableHeaderCell>
              <TableHeaderCell>Departamento</TableHeaderCell>
              <TableHeaderCell>Accion</TableHeaderCell>
            </TableRow>
          </template>
          <template #default>
            <TableRow v-for="(user, i) in users.data" :key="user.id" class="border-b">
              <TableDataCell>{{ from + i }}</TableDataCell>
              <TableDataCell>{{ user.name }}</TableDataCell>
              <TableDataCell>{{ user.email }}</TableDataCell>
              <TableDataCell>{{ user.department }}</TableDataCell>
              <TableDataCell class="space-x-4">
                <Link :href="route('users.edit', user.id)" as="button"
                  class="px-2 py-2 text-white font-semibold bg-blue-500 hover:bg-blue-600 rounded">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                  stroke="currentColor" class="w-6 h-6">
                  <path stroke-linecap="round" stroke-linejoin="round"
                    d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                </svg>
                </Link>
                <button @click.prevent="confirmDeleteuser(user.id)"
                  class="px-2 py-2 text-white font-semibold bg-red-500 hover:bg-red-600 rounded">
                  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round"
                      d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                  </svg>
                </button>
              </TableDataCell>
            </TableRow>
          </template>
        </Table>
        <div class="m-2 p-2">
          <Pagination :links="users.links" />
        </div>
      </div>
    </div>
  </AdminLayout>
</template>

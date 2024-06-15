<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import { Head, Link, useForm } from "@inertiajs/vue3";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import VueMultiselect from "vue-multiselect";
import Table from "@/Components/Table.vue";
import TableRow from "@/Components/TableRow.vue";
import TableHeaderCell from "@/Components/TableHeaderCell.vue";
import TableDataCell from "@/Components/TableDataCell.vue";
import { onMounted, watch } from "vue";

const props = defineProps({
    role: {
        type: Object,
        required: true,
    },
    permissions: Array,
});

const form = useForm({
    name: props.role.name,
    permissions: [],
});

onMounted(() => {
    form.permissions = props.role?.permissions;
});

watch(
    () => props.role,
    () => (form.permissions = props.role?.permissions)
);
</script>

<template>

    <Head title="Actualizar rol" />

    <AdminLayout>
        <div class="fullscreen mx-auto py-4">
            <div class="flex justify-between">
                <Link :href="route('roles.index')"
                    class="px-3 py-2 text-white font-semibold bg-gray-500 hover:bg-gray-600 rounded">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="w-5 h-5 inline-block align-text-top mr-1">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="m11.25 9-3 3m0 0 3 3m-3-3h7.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                </svg>
                Regresar</Link>
            </div>

            <div class="mt-6 fullscreen mx-auto bg-slate-100 shadow-lg rounded-lg p-6">
                <h1 class="text-2xl font-semibold text-indigo-700">
                    Actualizar ROL
                </h1>
                <form @submit.prevent="form.put(route('roles.update', role.id))">
                    <div class="mt-4">
                        <InputLabel for="name" value="Nombre" />

                        <TextInput id="name" type="text" class="mt-1 block w-full" v-model="form.name" autofocus
                            autocomplete="username" />

                        <InputError class="mt-2" :message="form.errors.name" />
                    </div>

                    <div class="mt-4">
                        <InputLabel for="permissions" value="Permisos" />
                        <VueMultiselect v-model="form.permissions" :options="permissions" :multiple="true"
                            :close-on-select="true" placeholder="Presiona para ver permisos" label="name"
                            track-by="id" />
                    </div>

                    <div class="flex items-center mt-4">
                        <PrimaryButton class="ms-0" :class="{ 'opacity-25': form.processing }"
                            :disabled="form.processing">
                            Actualizar
                        </PrimaryButton>
                    </div>
                </form>
            </div>
            <div class="mt-6 fullscreen mx-auto bg-slate-100 shadow-lg rounded-lg p-6">
                <h1 class="text-2xl text-center font-semibold text-indigo-700">
                    PERMISOS
                </h1>
                <div class="bg-slate-100">
                    <Table>
                        <template #header>
                            <TableRow>
                                <TableHeaderCell>ID</TableHeaderCell>
                                <TableHeaderCell>Nombre</TableHeaderCell>

                                <TableHeaderCell class="flex justify-end mx-4">
                                Accion
                                </TableHeaderCell>
                            </TableRow>
                        </template>
                        <template #default>
                            <TableRow v-for="rolePermission in role.permissions" :key="rolePermission.id"
                                class="border-b">
                                <TableDataCell>{{
                                    rolePermission.id
                                    }}</TableDataCell>
                                <TableDataCell>{{
                                    rolePermission.name
                                    }}</TableDataCell>
                                <TableDataCell class="flex justify-end mx-4">
                                    <Link :href="route('roles.permissions.destroy', [
                                        role.id,
                                        rolePermission.id,
                                    ])
                                        " method="delete" as="button"
                                        class="px-3 py-2 text-white font-semibold bg-red-500 hover:bg-red-600 rounded">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                                    </svg>
                                    </Link>
                                </TableDataCell>
                            </TableRow>
                        </template>
                    </Table>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
<style src="vue-multiselect/dist/vue-multiselect.css"></style>
<style>
.fullscreen {
  min-width: 80vw; /* 100% del ancho del viewport */
}
</style>

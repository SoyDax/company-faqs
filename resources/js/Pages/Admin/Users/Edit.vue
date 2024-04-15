<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import { Head, Link, useForm } from "@inertiajs/vue3";

import VueMultiselect from "vue-multiselect";
import Table from "@/Components/Table.vue";
import TableRow from "@/Components/TableRow.vue";
import TableHeaderCell from "@/Components/TableHeaderCell.vue";
import TableDataCell from "@/Components/TableDataCell.vue";
import { onMounted, watch, ref } from "vue";

const props = defineProps({
    user: {
        type: Object,
        required: true,
    },
    roles: Array,
    permissions: Array,
    departments: Array,
});

const form = useForm({
    name: props.user?.name,
    email: props.user?.email,
    department_id: props.user?.department_id, // Agrega department_id al formulario
    roles: [],
    permissions: [],
});

const selectedDepartment = ref(null); // Crea una referencia para el departamento seleccionado

watch(selectedDepartment, (newVal) => {
    if (newVal) {
        form.department_id = newVal.id; // Actualiza el department_id en el formulario cuando se selecciona un nuevo departamento
    }
});

const submit = () => {
    form.put(route("users.update", props.user?.id));
};

onMounted(() => {
    form.permissions = props.user?.permissions;
    form.roles = props.user?.roles;
    selectedDepartment.value = props.user?.department_id;
});
watch(
    () => props.user,
    () => {
        (form.permissions = props.user?.permissions),
            (form.roles = props.user?.roles);
        selectedDepartment.value = props.user?.department_id;
    }
);
</script>

<template>
    <AdminLayout>
        <div class="max-w-7xl mx-auto py-4">
            <div class="flex justify-between">
                <Link :href="route('users.index')"
                    class="px-3 py-2 text-white font-semibold bg-gray-500 hover:bg-gray-600 rounded">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="w-5 h-5 inline-block align-text-top mr-1">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="m11.25 9-3 3m0 0 3 3m-3-3h7.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                </svg>
                Regresar</Link>
            </div>

            <div class="mt-6 max-w-6xl mx-auto bg-slate-100 shadow-lg rounded-lg p-6">
                <h1 class="text-2xl font-semibold text-indigo-700">
                    EDITAR NUEVO USUARIO
                </h1>
                <form @submit.prevent="submit">
                    <div class="mt-4">
                        <InputLabel for="name" value="Name" />

                        <TextInput id="name" type="text" class="mt-1 block w-full" v-model="form.name" required
                            autofocus autocomplete="name" />

                        <InputError class="mt-2" :message="form.errors.name" />
                    </div>

                    <div class="mt-4">
                        <InputLabel for="email" value="Email" />

                        <TextInput id="email" type="email" class="mt-1 block w-full" v-model="form.email" required
                            autocomplete="username" />

                        <InputError class="mt-2" :message="form.errors.email" />
                    </div>

                    <div class="mt-4">
                        <InputLabel for="departments" value="Departamentos" />
                        <VueMultiselect v-model="selectedDepartment" :options="departments" :multiple="false"
                            :close-on-select="true" placeholder="Presiona para ver departamento" label="name"
                            track-by="id" />
                    </div>

                    <div class="mt-4">
                        <InputLabel for="roels" value="Roles" />
                        <VueMultiselect v-model="form.roles" :options="roles" :multiple="true" :close-on-select="true"
                            placeholder="Presiona para ver permisos" label="name" track-by="id" />
                    </div>

                    <div class="mt-4">
                        <InputLabel for="permissions" value="Permisos" />
                        <VueMultiselect v-model="form.permissions" :options="permissions" :multiple="true"
                            :close-on-select="true" placeholder="Presiona para ver permisos" label="name"
                            track-by="id" />
                    </div>

                    <div class="flex items-center justify-end mt-4">
                        <PrimaryButton class="ms-4" :class="{ 'opacity-25': form.processing }"
                            :disabled="form.processing">
                            Actualziar Usuario
                        </PrimaryButton>
                    </div>
                </form>
            </div>
        </div>

        <!-- <div class="mt-6 max-w-6xl mx-auto bg-slate-100 shadow-lg rounded-lg p-6">
        <h1 class="text-2xl text-center font-semibold text-indigo-700">DEPARTAMENTO</h1>
        <div class="bg-slate-100">
            <Table>
                <template #header>
                    <TableRow>
                        <TableHeaderCell>ID</TableHeaderCell>
                        <TableHeaderCell>Nombre</TableHeaderCell>

                        <TableHeaderCell>Accion</TableHeaderCell>
                    </TableRow>
                </template>
<template #default>
                    <TableRow v-for="userDepartment in user.department_id" :key="userDepartment.id" class="border-b">
                        <TableDataCell>{{ userDepartment.id }}</TableDataCell>
                        <TableDataCell>{{ userDepartment.name }}</TableDataCell>
                        <TableDataCell class="space-x-4">

                            <Link href="">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                            </svg>

                            </Link>
                        </TableDataCell>
                    </TableRow>
                </template>
</Table>
</div>
</div> -->

        <div class="mt-6 max-w-6xl mx-auto bg-slate-100 shadow-lg rounded-lg p-6">
            <h1 class="text-2xl text-center font-semibold text-indigo-700">
                ROLES
            </h1>
            <div class="bg-slate-100">
                <Table>
                    <template #header>
                        <TableRow>
                            <TableHeaderCell>ID</TableHeaderCell>
                            <TableHeaderCell>Nombre</TableHeaderCell>

                            <TableHeaderCell>Accion</TableHeaderCell>
                        </TableRow>
                    </template>
                    <template #default>
                        <TableRow v-for="userRole in user.roles" :key="userRole.id" class="border-b">
                            <TableDataCell>{{ userRole.id }}</TableDataCell>
                            <TableDataCell>{{ userRole.name }}</TableDataCell>
                            <TableDataCell class="space-x-4">
                                <Link :href="route('users.roles.destroy', [
                                    user.id,
                                    userRole.id,
                                ])
                                    " method="DELETE" as="button"
                                    class="px-3 py-2 text-gray font-semibold bg-slate-200 hover:bg-slate-300 rounded"
                                    preserve-scroll>
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

        <div class="mt-6 max-w-6xl mx-auto bg-slate-100 shadow-lg rounded-lg p-6">
            <h1 class="text-2xl text-center font-semibold text-indigo-700">
                PERMISOS
            </h1>
            <div class="bg-slate-100">
                <Table>
                    <template #header>
                        <TableRow>
                            <TableHeaderCell>ID</TableHeaderCell>
                            <TableHeaderCell>Nombre</TableHeaderCell>

                            <TableHeaderCell>Accion</TableHeaderCell>
                        </TableRow>
                    </template>
                    <template #default>
                        <TableRow v-for="userPermission in user.permissions" :key="userPermission.id" class="border-b">
                            <TableDataCell>{{
                                userPermission.id
                                }}</TableDataCell>
                            <TableDataCell>{{
                                userPermission.name
                                }}</TableDataCell>
                            <TableDataCell class="space-x-4">
                                <Link :href="route('users.permissions.destroy', [
                                    user.id,
                                    userPermission.id,
                                ])
                                    " method="DELETE" as="button"
                                    class="px-3 py-2 text-gray font-semibold bg-slate-200 hover:bg-slate-300 rounded"
                                    preserve-scroll>
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
    </AdminLayout>
</template>

<style src="vue-multiselect/dist/vue-multiselect.css"></style>

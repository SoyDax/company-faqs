<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import { Head, Link, useForm } from "@inertiajs/vue3";
import { onMounted, watch, ref } from "vue";
import VueMultiselect from "vue-multiselect";

defineProps({
    roles: Array,
    permissions: Array,
    departments: Array,
});

const form = useForm({
    name: "",
    email: "",
    password: "",
    password_confirmation: "",
    department_id: "",
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
    form.post(route("users.store"), {
        onFinish: () => form.reset("password", "password_confirmation"),
    });
};
</script>

<template>

    <Head title="Crear Usuario" />
    <AdminLayout>
        <div class="fullscreen mx-auto py-4">
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

            <div class="mt-6 fullscreen mx-auto bg-slate-100 shadow-lg rounded-lg p-6">
                <h1 class="text-2xl font-semibold text-indigo-700">
                    CREAR NUEVO USUARIO
                </h1>
                <form @submit.prevent="submit">
                    <div>
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
                        <InputLabel for="password" value="Password" />

                        <TextInput id="password" type="password" class="mt-1 block w-full" v-model="form.password"
                            required autocomplete="new-password" />

                        <InputError class="mt-2" :message="form.errors.password" />
                    </div>

                    <div class="mt-4">
                        <InputLabel for="password_confirmation" value="Confirm Password" />

                        <TextInput id="password_confirmation" type="password" class="mt-1 block w-full"
                            v-model="form.password_confirmation" required autocomplete="new-password" />

                        <InputError class="mt-2" :message="form.errors.password_confirmation" />
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
                            Crear Usuario
                        </PrimaryButton>
                    </div>
                </form>
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

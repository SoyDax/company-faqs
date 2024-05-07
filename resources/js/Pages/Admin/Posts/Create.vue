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
    categories: Array,
    subcategories: Array,
    departments: Array,
});

const form = useForm({
    title: "",
    description: "",
    category_id: "",
    sub_category_id: "",
    department_id: "",
});

const selectedCategory = ref(null); // Crea una referencia para la categoria seleccionado
watch(selectedCategory, (newVal) => {
    if (newVal) {
        form.category_id = newVal.id; // Actualiza la categoria_id en el formulario cuando se selecciona un nuevo categoria
    }
});

const selectedSubCategory = ref(null); // Crea una referencia para la subcategoria seleccionado
watch(selectedSubCategory, (newVal) => {
    if (newVal) {
        form.sub_category_id = newVal.id; // Actualiza la subcategoria_id en el formulario cuando se selecciona un nuevo subcategoria
    }
});

const selectedDepartment = ref(null); // Crea una referencia para la subcategoria seleccionado
watch(selectedDepartment, (newVal) => {
    if (newVal) {
        form.department_id = newVal.id; // Actualiza la subcategoria_id en el formulario cuando se selecciona un nuevo subcategoria
    }
});
</script>

<template>

    <Head title="Crear nuevo Faqs" />

    <AdminLayout>
        <div class="max-w-7xl mx-auto py-4">
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

            <div class="mt-6 max-w-6xl mx-auto bg-slate-100 shadow-lg rounded-lg p-6">
                <h1 class="text-2xl font-semibold text-indigo-700">
                    CREAR NUEVO FAQ
                </h1>
                <form @submit.prevent="form.post(route('posts.store'))">
                    <div class="mt-4">
                        <InputLabel for="title" value="Titulo:" />

                        <TextInput id="title" type="text" class="mt-1 block w-full" v-model="form.title" autofocus
                            autocomplete="title" />

                        <InputError class="mt-2" :message="form.errors.title" />
                    </div>

                    <div class="mt-4">
                        <InputLabel for="categories" value="Categoria:" />
                        <VueMultiselect v-model="selectedCategory" :options="categories" :multiple="false"
                            :close-on-select="true" placeholder="Presiona para ver categorias" label="name"
                            track-by="id" />
                    </div>

                    <div class="mt-4">
                        <InputLabel for="subcategories" value="SubCategoria:" />
                        <VueMultiselect v-model="selectedSubCategory" :options="subcategories" :multiple="false"
                            :close-on-select="true" placeholder="Presiona para ver SubCategorias" label="name"
                            track-by="id" />
                    </div>

                    <div class="mt-4">
                        <InputLabel for="departments" value="Departamento:" />
                        <VueMultiselect v-model="selectedDepartment" :options="departments" :multiple="false"
                            :close-on-select="true" placeholder="presiona para ver Departamento" label="name"
                            track-by="id" />
                    </div>

                    <div class="mt-4">
                        <InputLabel for="description" value="Descripcion:" />

                        <TextInput id="description" type="text" class="mt-1 block w-full" v-model="form.description" autofocus
                            autocomplete="description" />

                        <InputError class="mt-2" :message="form.errors.description" />
                    </div>



                    <div class="flex items-center mt-4">
                        <PrimaryButton class="ms-0" :class="{ 'opacity-25': form.processing }"
                            :disabled="form.processing">
                            Crear
                        </PrimaryButton>
                    </div>
                </form>
            </div>
        </div>
    </AdminLayout>
</template>
<style src="vue-multiselect/dist/vue-multiselect.css"></style>

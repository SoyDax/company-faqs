<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import { Head, Link, useForm } from "@inertiajs/vue3";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import { Editor, tinymce, contentUiSkinCss, contentCss } from '@/composables/tinymceImports';
import { loadCloudinaryWidget, openUploadWidget } from '@/composables/cloudinaryWidget';
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import { onMounted, watch, ref } from "vue";
import VueMultiselect from "vue-multiselect";
import { usePermission } from "@/composables/permissions";

//importa el script de cloudinary para subir imagenes en el editor de texto de tinymce 
onMounted(loadCloudinaryWidget);

const props = defineProps({
    post: {
        type: Object,
        required: true,
    },
    categories: {
        type: Object,
        required: true,
    },
    subcategories: {
        type: Object,
        required: true,
    },
    departments: {
        type: Object,
        required: true,
    },
});
const { hasPermissions } = usePermission();
const form = useForm({
    title: props.post?.title,
    category_id: props.post?.category_id,
    sub_category_id: props.post?.sub_category_id,
    department_id: props.post?.department_id,
    description: props.post?.description,
});

//la configuración de TinyMCE para el editor de texto de descripción
const tinymeceConfig = {
    content_css: false,
    skin: false,
    content_style: contentUiSkinCss.toString() + '\n' + contentCss.toString(),
    height: 500,
    menubar: true,
    promotion: false,
    plugins: [
        'link', 'lists', 'image', 'anchor', 'wordcount', 'media', 'autolink', 'charmap', 'codesample', 'searchreplace', 'table', 'visualblocks', 'fullscreen', 'quickbars' , 
    ],
    toolbar:
        'undo redo | aidialog aishortcuts | formatselect | styles fontsizeinput |  bold italic backcolor | \
    alignleft aligncenter alignright alignjustify | \
    bullist numlist outdent indent | removeformat | table link image | fullscreen',
    quickbars_insert_toolbar: 'quicktable image media codesample',
  quickbars_selection_toolbar: 'bold italic underline | blocks | bullist numlist | blockquote quicklink',
    
}

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

const submit = () => {
    form.put(route("posts.update", props.post?.id));
};
onMounted(() => {
    selectedCategory.value = props.post?.category_id;
    selectedSubCategory.value = props.post?.sub_category_id;
    selectedDepartment.value = props.post?.department_id;
});
watch(
    () => props.post,
    () => {
        selectedCategory.value = props.post?.category_id;
        selectedSubCategory.value = props.post?.sub_category_id;
        selectedDepartment.value = props.post?.department_id;
    }
);
</script>

<template>

    <Head title="Actualizar Faqs" />

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
                    ACTUALIZAR FAQ
                </h1>
                <form @submit.prevent="submit">
                    <div class="mt-4">
                        <InputLabel for="title" value="Publicacion" />

                        <TextInput id="title" type="text" class="mt-1 block w-full" v-model="form.title" autofocus
                            autocomplete="title" />

                        <InputError class="mt-2" :message="form.errors.title" />
                    </div>
                    <div class="mt-4">
                        <InputLabel for="categories" value="Categoria:" />
                        <VueMultiselect v-model="selectedCategory" :options="categories" :multiple="false"
                            :close-on-select="true" :placeholder="post.category" label="name" track-by="id" />
                    </div>

                    <div class="mt-4">
                        <InputLabel for="subcategories" value="SubCategoria:" />
                        <VueMultiselect v-model="selectedSubCategory" :options="subcategories" :multiple="false"
                            :close-on-select="true" :placeholder="post.subcategory" label="name"
                            track-by="id" />
                    </div>

                    <template v-if="hasPermissions(['Editar departamento'])">
                    <div class="mt-4">
                        <InputLabel for="departments" value="Departamento:" />
                        <VueMultiselect v-model="selectedDepartment" :options="departments" :multiple="false"
                            :close-on-select="true" :placeholder="post.department" label="name"
                            track-by="id" />
                    </div>
                    </template>

                    <div class="mt-4">
                        <InputLabel for="description" value="Descripcion:" />
                        <div class="flex items-center mt-4 mb-3 ">    
                            <PrimaryButton class="ms-0" @click="openUploadWidget($event)">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 mr-2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 0 0 1.5-1.5V6a1.5 1.5 0 0 0-1.5-1.5H3.75A1.5 1.5 0 0 0 2.25 6v12a1.5 1.5 0 0 0 1.5 1.5Zm10.5-11.25h.008v.008h-.008V8.25Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
                                </svg>
                                Cargar imagen
                            </PrimaryButton>
                        </div>
                        <Editor                                           
                            :init="tinymeceConfig"
                            id="description"
                            v-model="form.description"
                            type="text"
                        />

                        <!-- <TextInput id="description" type="text" class="mt-1 block w-full" v-model="form.description"
                            autofocus autocomplete="description" /> -->

                        <InputError class="mt-2" :message="form.errors.description" />
                    </div>

                    <div class="flex items-center mt-4">
                        <PrimaryButton class="ms-0" :class="{ 'opacity-25': form.processing }"
                            :disabled="form.processing">
                            Actualizar
                        </PrimaryButton>
                    </div>
                </form>
            </div>
        </div>
    </AdminLayout>
</template>
<style src="vue-multiselect/dist/vue-multiselect.css"></style>

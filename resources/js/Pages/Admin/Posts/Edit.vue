<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import {
    Head,
    Link,
    useForm
} from '@inertiajs/vue3';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';

import Table from '@/Components/Table.vue';
import TableRow from '@/Components/TableRow.vue';
import TableHeaderCell from '@/Components/TableHeaderCell.vue';
import TableDataCell from '@/Components/TableDataCell.vue';
import { onMounted, watch } from 'vue';

const props = defineProps( {
    post: {
        type: Object,
        required: true
    },

    
});

const form = useForm({
    title: props.post?.title,
   
});

</script>

<template>
<Head title="Actualizar Faqs" />

<AdminLayout>
    <div class="max-w-7xl mx-auto py-4">
        <div class="flex justify-between">

            <Link :href="route('posts.index')" class="px-3 py-2 text-white font-semibold bg-gray-500 hover:bg-gray-600 rounded">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 inline-block align-text-top mr-1">
                <path stroke-linecap="round" stroke-linejoin="round" d="m11.25 9-3 3m0 0 3 3m-3-3h7.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
            </svg>
            Regresar</Link>
        </div>

        <div class="mt-6 max-w-6xl mx-auto bg-slate-100 shadow-lg rounded-lg p-6">

            <h1 class="text-2xl font-semibold text-indigo-700">Actualizar Faqs</h1>
            <form @submit.prevent="form.put(route('posts.update', post.id))">
                <div class="mt-4">
                    <InputLabel for="title" value="Publicacion" />

                    <TextInput id="title" type="text" class="mt-1 block w-full" v-model="form.title" autofocus autocomplete="title" />

                    <InputError class="mt-2" :message="form.errors.title" />
                </div>
                
                <div class="flex items-center mt-4">

                    <PrimaryButton class="ms-0" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                        Actualizar
                    </PrimaryButton>
                </div>
            </form>
        </div>
       
    </div>
</AdminLayout>
</template>

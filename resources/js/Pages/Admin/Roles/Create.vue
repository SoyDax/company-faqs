<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';

import { Head, Link, useForm } from '@inertiajs/vue3';

import Checkbox from '@/Components/Checkbox.vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';


const form = useForm({
  name: "",
  permissions: [],
});
</script>


<template>
    <Head title="Crear nuevo rol" />

    <AdminLayout>
        <div class="max-w-7xl mx-auto py-4">
        <div class="flex justify-between">
        
          <Link
            :href="route('roles.index')"
            class="px-3 py-2 text-white font-semibold bg-indigo-500 hover:bg-indigo-700 rounded"
            >Regresar</Link
          >
        </div>
        <div class="mt-6 max-w-md mx-auto">
          <form @submit.prevent="form.post(route('roles.store'))">
            <div>
                <InputLabel for="name" value="Name" />

                <TextInput
                    id="name"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.name"
                    required
                    autofocus
                    autocomplete="username"
                />

                <InputError class="mt-2" :message="form.errors.name" />
            </div>

     
            <div class="flex items-center mt-4">
           
                <PrimaryButton class="ms-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                    Create
                </PrimaryButton>
            </div>
        </form>
        </div>
      </div>
    </AdminLayout>
</template>

<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';

import { Head, Link } from '@inertiajs/vue3';

import Table from '@/Components/Table.vue';
import TableRow from '@/Components/TableRow.vue';
import TableHeaderCell from '@/Components/TableHeaderCell.vue';
import TableDataCell from '@/Components/TableDataCell.vue';

defineProps(['departments']);

</script>

<template>
    <Head title="Department" />

    <AdminLayout>
        <div class="max-w-7xl mx-auto py-4">
        <div class="flex justify-between">
          <h1>Department Index Page</h1>
          <Link
            :href="route('users.create')"
            class="px-3 py-2 text-white font-semibold bg-indigo-500 hover:bg-indigo-700 rounded"
            >Nuevo departamento</Link
          >
        </div>
        <div class="mt-6">
          <Table>
            <template #header>
              <TableRow>
                <TableHeaderCell>ID</TableHeaderCell>
                <TableHeaderCell>Departamento</TableHeaderCell>
             
                <TableHeaderCell>Action</TableHeaderCell>
              </TableRow>
            </template>
            <template #default>
              <TableRow v-for="department in departments" :key="department.id" class="border-b">
                <TableDataCell>{{ department.id }}</TableDataCell>
                <TableDataCell>{{ department.name }}</TableDataCell>
                <TableDataCell>edit/Delete</TableDataCell>
             
              
              </TableRow>
            </template>
          </Table>
        </div>
      </div>
    </AdminLayout>
</template>

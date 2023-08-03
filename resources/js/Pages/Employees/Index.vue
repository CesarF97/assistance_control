<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { useAlertsStore } from '../../Stores/alerts.js';
import DangerButton from '../../Components/DangerButton.vue';
import Swal from 'sweetalert2';
const alerts = useAlertsStore();
const props = defineProps({
    employees: {
        type: Object,
    }
})
const form = useForm({
    user: {}
});

const deleteEmployee = async (user) => {
    const alert = Swal.mixin({
        buttonsStyling: true
    })
    try {
        const result = await alert.fire({
            title: `Seguro que quiere eliminar al empleado ${user.name} ?`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: `<i class"fa-solid fa-check"></i>Si, eliminalo`,
            cancelButtonText: `<i class"fa-solid fa-ban"></i>Cancelar`,
        })

        if (result.isConfirmed) {
            form.delete(route('employees.destroy', user));
        }
    } catch (error) {
        console.error(error)
    }
}

const setFullName = (employee) => {
    return `${employee.name} ${employee.last_name}`
}

</script>

<template>
    <Head title="Empleados" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Empleados</h2>
        </template>

        <div class="py-2">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white grid v-screen place-items-center">
                    <div class="mt-3 mb-3 flex"></div>
                </div>

                <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                    <table class="w-full text-sm text-left">
                        <thead class="text-xs text-zinc-50 uppercase bg-neutral-800 text-zinc-50">
                            <tr>
                                <th scope="col" class="px-6 py-3">
                                    id
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Nombre
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Cedula
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Rif
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Correo
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Acción
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="border-b text-dark-500 even:bg-gray-200" v-for="(employee, index ) in employees.data" :key="employee.id">
                                <th scope="row"
                                    class="px-6 py-4">
                                    {{ employee.id }}
                                </th>
                                <td class="px-6 py-4">
                                    {{ setFullName(employee) }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ employee.ci }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ employee.rif }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ employee.email }}
                                </td>
                                <td class="px-6 py-4">
                                    <a href="#"
                                        class="font-medium text-blue-600 dark:text-blue-500 hover:underline me-2">Edit</a>

                                    <DangerButton @click="deleteEmployee(employee)">
                                        <i class="fa-solid fa-trash"></i>
                                    </DangerButton>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>


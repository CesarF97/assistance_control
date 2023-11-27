<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { useAlertsStore } from "@/Stores/alerts.js";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import SelectInput from "@/Components/SelectInput.vue";
import TextInput from "@/Components/TextInput.vue";
import { defineProps } from "vue";
import { useForm, Head } from "@inertiajs/vue3";
import moment from "moment";
const props = defineProps(["employees", "employee_history"]);
const alerts = useAlertsStore();
const form = useForm({
  date_from: "",
  date_to: "",
  employee_id: null,
});

const updateEmployee = (e) => {
  form.employee_id = e;
};

const search = () => {
  if (!form.employee_id)
    return alerts.warning("Por favor seleccione un Empleado");

  form.post(route("employees.history.search"), {
    onSuccess: () => {},
  });
};

const formatDate = (date) => {
  return moment(date).format("YYYY-MM-DD, HH:mm:ss");
};
</script>

<template>
  <Head title="Empleados" />

  <AuthenticatedLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Empleados
      </h2>
    </template>

    <div class="py-2">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 bg-white pt-4 rounded-lg">
        <div class="flex mb-2">
          <form class="flex" @submit.prevent="search">
            <div class="w-1/4">
              Empleado:
              <SelectInput
                id="date_from"
                v-model="form.employee_id"
                @update="updateEmployee"
                :options="props.employees.data"
              />
            </div>
            <div class="w-1/4 ms-4">
              Desde:
              <TextInput
                id="date_from"
                type="date"
                v-model="form.date_from"
                required
              />
            </div>
            <div class="w-1/4">
              Hasta:
              <TextInput
                id="date_to"
                type="date"
                v-model="form.date_to"
                required
              />
            </div>
            <PrimaryButton class="mt-6">
              <i class="fa-solid fa-magnifying-glass"></i>
            </PrimaryButton>
          </form>
        </div>
        <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
          <table class="w-full text-sm text-left">
            <thead class="text-xs text-zinc-50 uppercase bg-neutral-800">
              <tr>
                <th scope="col" class="px-6 py-3">Tipo</th>
                <th scope="col" class="px-6 py-3">Fecha</th>
              </tr>
            </thead>
            <tbody>
              <tr
                class="border-b text-dark-500 even:bg-gray-200"
                v-for="history in props.employee_history"
                :key="history.id"
              >
                <td class="px-6 py-4">
                  {{ history.type == "OUT" ? "Entrada" : "Salida" }}
                </td>
                <td class="px-6 py-4">{{ formatDate(history.created_at) }}</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

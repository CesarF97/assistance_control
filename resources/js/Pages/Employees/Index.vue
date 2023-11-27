<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, useForm } from "@inertiajs/vue3";
import { useAlertsStore } from "@/Stores/alerts.js";
import DangerButton from "@/Components/DangerButton.vue";
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import InputError from "@/Components/InputError.vue";
import Swal from "sweetalert2";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import Modal from "@/Components/Modal.vue";
import { ref, nextTick, defineProps } from "vue";
import VueTailwindPagination from "@ocrv/vue-tailwind-pagination";
import { Link } from '@inertiajs/vue3'

const alerts = useAlertsStore();
const selectedEmployee = ref({});
const showModal = ref(false);
const showQRModal = ref(false);
const title = ref("");
const operation = ref(1);
const nameInput = ref(null);
const id = ref("");

const props = defineProps(["employees"]);

const form = useForm({
  name: "",
  email: "",
  last_name: "",
  ci: "",
  rif: "",
  phone: "",
});

const formPage = useForm({});

const onPageClick = (event) => {
  formPage.get(
    route("employees.index", {
      page: event,
    })
  );
};

const openModal = (op, employee) => {
  showModal.value = true;
  nextTick(() => nameInput.value.focus());
  operation.value = op;
  id.value = employee;
  if (op == 1) {
    title.value = "Crear Empleado";
  } else {
    title.value = "Editar Empleado";
    form.name = employee.name;
    form.email = employee.email;
    form.last_name = employee.last_name;
    form.ci = employee.ci;
    form.rif = employee.rif;
    form.phone = employee.phone;
  }
};

const openQRModal = (employee) => {
  selectedEmployee.value = employee;
  showQRModal.value = true;
};

const closeModal = () => {
  showModal.value = false;
  form.reset();
};

const closeQRModal = () => {
  showQRModal.value = false;
};

const save = () => {
  if (operation.value == 1) {
    form.post(route("employees.store"), {
      onSuccess: () => {
        alerts.success("Empleado Creado");
        form.reset();
        closeModal();
      },
    });
  } else {
    form.put(route("employees.update", id.value), {
      onSuccess: () => {
        alerts.success("Empleado Actualizado");
        form.reset();
        closeModal();
      },
    });
  }
};

const deleteEmployee = async (user) => {
  const alert = Swal.mixin({
    buttonsStyling: true,
  });
  try {
    const result = await alert.fire({
      title: `Seguro que quiere eliminar al empleado ${user.name} ?`,
      icon: "question",
      showCancelButton: true,
      confirmButtonText: `<i class"fa-solid fa-check"></i>Si, eliminalo`,
      cancelButtonText: `<i class"fa-solid fa-ban"></i>Cancelar`,
    });

    if (result.isConfirmed) {
      form.delete(route("employees.destroy", user));
    }
  } catch (error) {
    console.error(error);
  }
};

const setFullName = (employee) => {
  return `${employee.name} ${employee.last_name}`;
};
const setDownloadName = (employee) => {
  return `${employee.name}_${employee.last_name}_qr.svg`;
}
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
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white grid v-screen place-items-center">
        </div>

        <PrimaryButton class="mb-2" @click="openModal(1)">
          <i class="fa-solid fa-plus me-1"></i>Registrar
        </PrimaryButton>
        <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
          <table class="w-full text-sm text-left">
            <thead class="text-xs text-zinc-50 uppercase bg-neutral-800">
              <tr>
                <th scope="col" class="px-6 py-3">id</th>
                <th scope="col" class="px-6 py-3">Nombre</th>
                <th scope="col" class="px-6 py-3">Cedula</th>
                <th scope="col" class="px-6 py-3">Rif</th>
                <th scope="col" class="px-6 py-3">Correo</th>
                <th scope="col" class="px-6 py-3">Acción</th>
              </tr>
            </thead>
            <tbody>
              <tr
                class="border-b text-dark-500 even:bg-gray-200"
                v-for="employee in employees.data"
                :key="employee.id"
              >
                <th scope="row" class="px-6 py-4">
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
                  <PrimaryButton class="me-2" @click="openQRModal(employee)">
                    <i class="fa-solid fa-eye"></i>
                  </PrimaryButton>

                  <SecondaryButton class="me-2" @click="openModal(2, employee)">
                    <i class="fa-solid fa-pencil"></i>
                  </SecondaryButton>

                  <Link class="me-2 inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150 cursor-pointer" :href="route('employee.assistances', employee)">
                    <i class="fa-solid fa-calendar-days"></i>
                  </Link>

                  <DangerButton @click="deleteEmployee(employee)">
                    <i class="fa-solid fa-trash"></i>
                  </DangerButton>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
        <!-- Paginador -->
        <div class="bg-white grid v-screen place-items-center">
          <VueTailwindPagination
            :current="employees.currentPage"
            :total="employees.total"
            :per-page="employees.per_page"
            @page-changed="onPageClick($event)"
            preserve-scroll
          >
          </VueTailwindPagination>
        </div>
      </div>
    </div>
    <!-- Modal de creacion / edicion -->
    <Modal :show="showModal" @close="closeModal">
      <div class="ms-10">
        <h2 class="p-4 pb-5 text-lg font.medium text-gray-900">
          {{ title }}
        </h2>
        <div class="px-5 pb-3">
          <InputLabel for="name" value="Nombre:"></InputLabel>
          <TextInput
            id="name"
            ref="nameInput"
            v-model="form.name"
            type="text"
            class="mt-1 block w-3/4"
            placeholder="Nombre"
          ></TextInput>
          <InputError :message="form.errors.name" class="mt-2"></InputError>
        </div>
        <div class="px-5 pb-3">
          <InputLabel for="last_name" value="Apellido:"></InputLabel>
          <TextInput
            id="last_name"
            v-model="form.last_name"
            type="text"
            class="mt-1 block w-3/4"
            placeholder="Apellido"
          ></TextInput>
          <InputError
            :message="form.errors.last_name"
            class="mt-2"
          ></InputError>
        </div>
        <div class="px-5 pb-3">
          <InputLabel for="email" value="Correo:"></InputLabel>
          <TextInput
            id="email"
            v-model="form.email"
            type="text"
            class="mt-1 block w-3/4"
            placeholder="Correo"
          ></TextInput>
          <InputError :message="form.errors.email" class="mt-2"></InputError>
        </div>
        <div class="px-5 pb-3">
          <InputLabel for="ci" value="Cedula:"></InputLabel>
          <TextInput
            id="ci"
            v-model="form.ci"
            type="text"
            class="mt-1 block w-3/4"
            placeholder="Cedula"
          ></TextInput>
          <InputError :message="form.errors.ci" class="mt-2"></InputError>
        </div>
        <div class="px-5 pb-3">
          <InputLabel for="rif" value="Rif:"></InputLabel>
          <TextInput
            id="rif"
            v-model="form.rif"
            type="text"
            class="mt-1 block w-3/4"
            placeholder="Rif"
          ></TextInput>
          <InputError :message="form.errors.rif" class="mt-2"></InputError>
        </div>
        <div class="px-5 pb-3">
          <InputLabel for="phone" value="Telefono:"></InputLabel>
          <TextInput
            id="phone"
            v-model="form.phone"
            type="text"
            class="mt-1 block w-3/4"
            placeholder="Telefono"
          ></TextInput>
          <InputError :message="form.errors.phone" class="mt-2"></InputError>
        </div>

        <div class="px-5 mb-5 mt-1 flex justify-around">
          <PrimaryButton :disabled="form.processing" @click="save">
            <i class="fa-solid fa-save"></i>
            <span class="ms-2">Guardar</span>
          </PrimaryButton>
          <SecondaryButton @click="closeModal" :disabled="form.processing">
            Cancelar
          </SecondaryButton>
        </div>
      </div>
    </Modal>
    <!-- Modal de QR -->
    <Modal :show="showQRModal" @close="closeQRModal" maxWidth="sm">
      <div class="mx-10 mb-5 flex justify-center items-center">
        <img
          class="mt-10"
          :src="selectedEmployee.qr"
          alt="QR"
          width="300"
          height="300"
        />
      </div>
      <div class="mx-10 mb-3 mt-5 flex justify-around">
        <PrimaryButton>
          <i class="fa-solid fa-save"></i>
          <a
            class="ms-2"
            :href="selectedEmployee.qr"
            :download="setDownloadName(selectedEmployee)"
            target="_blank"
            >Descargar</a
          >
        </PrimaryButton>
        <SecondaryButton @click="closeQRModal"> Cancelar </SecondaryButton>
      </div>
    </Modal>
  </AuthenticatedLayout>
</template>

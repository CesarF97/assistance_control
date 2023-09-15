<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { ref, defineProps, onMounted, watch} from "vue";
import { Head, useForm } from "@inertiajs/vue3";
import FullCalendar from "@fullcalendar/vue3";
import dayGridPlugin from "@fullcalendar/daygrid";
import timeGridPlugin from "@fullcalendar/timegrid";
import listPlugin from "@fullcalendar/list";
import interactionPlugin from "@fullcalendar/interaction";
import Swal from "sweetalert2";
import Modal from "@/Components/Modal.vue";
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import InputError from "@/Components/InputError.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import DangerButton from "@/Components/DangerButton.vue";
import { useAlertsStore } from "@/Stores/alerts.js";
import axios from "axios";


const props = defineProps(["employee"]);
onMounted(() => {
  getAssistances()
})
const alerts = useAlertsStore();

const form = useForm({
  title: "",
  date: "",
  id: "",
});

const data_events = ref([]);

const showModal = ref(false);

const getAssistances = async () => {
  const {data} = await axios.get(route("assistances.by.employee", props.employee.id));
  data_events.value = data;
}
const openModal = (arg) => {
  showModal.value = true;
  console.log(arg.event.start, arg.event.title);
  form.title = arg.event.title;
  form.date = arg.event.start.toLocaleString("en");
  form.id = arg.event.id;
};

const closeModal = () => {
  showModal.value = false;
  form.reset();
};

const setFullName = (employee) => {
  return `${employee.name} ${employee.last_name}`;
};

const options = ref({
  plugins: [dayGridPlugin, timeGridPlugin, listPlugin, interactionPlugin],
  initialView: "dayGridMonth",
  locale: "es",
  headerToolbar: {
    right: "prev,next today",
    center: "title",
    left: "dayGridMonth,listWeek",
  },
  events: data_events,
  eventClick: (arg) => {
    openModal(arg);
  },
});
watch(getAssistances, ()=> {
  options.events = data_events.value
})
const deleteEvent = async () => {
  const alert = Swal.mixin({
    buttonsStyling: true,
  });
  try {
    const result = await alert.fire({
      title: `Esta seguro? Esta accion no se puede revertir`,
      icon: "question",
      showCancelButton: true,
      confirmButtonText: `<i class"fa-solid fa-check"></i>Si, eliminalo`,
      cancelButtonText: `<i class"fa-solid fa-ban"></i>Cancelar`,
    });

    if (result.isConfirmed) {
      form.delete(route("assistances.destroy", form.id), {
        onSuccess: () => {
          alerts.success("Empleado Actualizado");
          form.reset();
          closeModal();
        },
      });
    }
  } catch (error) {
    console.error(error);
  }
};

const setClass = (type) => {
  return type == "INGRESO"
    ? "bg-green-200 text-black-500"
    : "bg-red-200 text-black-500";
};

</script>

<template>
  <Head title="Empleados" />

  <AuthenticatedLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Historial de Asistencia de {{ setFullName(props.employee) }}
      </h2>
    </template>

    <div class="py-2">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white grid v-screen place-items-center">
          <div class="mt-3 mb-3 flex"></div>
        </div>

        <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
          <FullCalendar :options="options">
            <template v-slot:eventContent="arg">
              <b class="mr-1">{{ arg.timeText }}</b>
              <p :class="setClass(arg.event.title)">{{ arg.event.title }}</p>
            </template>
          </FullCalendar>
          <Modal :show="showModal" @close="closeModal">
            <div class="ms-10">
              <h2 class="p-4 pb-5 text-lg font.medium text-gray-900">
                Eliminar registro de asistencia
              </h2>
              <div class="px-5 pb-3">
                <InputLabel for="title" value="Tipo:"></InputLabel>
                <TextInput
                  id="title"
                  ref="titleInput"
                  v-model="form.title"
                  type="text"
                  class="mt-1 block w-3/4"
                  placeholder="Tipo"
                  disabled
                ></TextInput>
                <InputError
                  :message="form.errors.title"
                  class="mt-2"
                ></InputError>
              </div>
              <div class="px-5 pb-3">
                <InputLabel for="date" value="Fecha:"></InputLabel>
                <TextInput
                  id="date"
                  v-model="form.date"
                  type="text"
                  class="mt-1 block w-3/4"
                  placeholder="Fecha"
                  disabled
                ></TextInput>
                <InputError
                  :message="form.errors.date"
                  class="mt-2"
                ></InputError>
              </div>

              <div class="px-5 mb-5 mt-1 flex justify-around">
                <DangerButton
                  :disabled="form.processing"
                  @click="deleteEvent"
                >
                  <i class="fa-solid fa-trash"></i>
                  <span class="ms-2">Eliminar</span>
                </DangerButton>
                <SecondaryButton
                  @click="closeModal"
                  :disabled="form.processing"
                >
                  Cancelar
                </SecondaryButton>
              </div>
            </div>
          </Modal>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

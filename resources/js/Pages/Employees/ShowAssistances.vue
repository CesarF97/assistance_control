<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { ref, defineProps } from "vue";
import FullCalendar from "@fullcalendar/vue3";
import dayGridPlugin from "@fullcalendar/daygrid";
import timeGridPlugin from "@fullcalendar/timegrid";
import listPlugin from "@fullcalendar/list";
import interactionPlugin from "@fullcalendar/interaction";

const props = defineProps(["employee", "assistances"]);

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
  events: props.assistances.data,
  eventClick: (arg) => {
    confirm('Seguro que quiere eliminar este registro?')
    console.log(arg.event.title,arg.event.id,arg.event.start)
  }
});

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
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<template>
  <Page>
    <Table title="Horários de Trabalho" :columns="columns">
      <template v-slot:addButton>
        <button type="button" class="btn btn-primary" @click="openModal('create')">
          <b><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor"
              class="mr-2 bi bi-plus-circle-fill" viewBox="0 0 16 16">
              <path
                d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM8.5 4.5a.5.5 0 0 0-1 0v3h-3a.5.5 0 0 0 0 1h3v3a.5.5 0 0 0 1 0v-3h3a.5.5 0 0 0 0-1h-3v-3z" />
            </svg> Adicionar</b>
        </button>
      </template>
      <template v-slot:tableBody>
        <tr v-for="row in rows.map(mapTableRows)" :key="row.id">
          <td class="text-center" v-for="(value, key) in row" :key="value">{{ value }}</td>
          <td class="justify-content-center d-flex gap-2">
            <button type="button" class="btn btn-success" @click="openModal('edit', row.id)">Edit</button>
            <button class="btn btn-danger" @click="deleteTimeslot(row.id)">Delete</button>
          </td>
        </tr>
      </template>
    </Table>
    <div class="mt-4">
      <Table title="Resumo das Horas Trabalhadas" :columns="hoursSummaryColumns">
        <template v-slot:tableBody>
          <tr>
            <td class="text-center">{{ `${totalNightHours.hours}`.padStart(2, '0') + ':' + `${totalNightHours.minutes?.toFixed(0)}`.padStart(2, '0') }}</td>
            <td class="text-center">{{ `${totalDayHours.hours}`.padStart(2, '0') + ':' + `${totalDayHours.minutes?.toFixed(0)}`.padStart(2, '0') }} </td>
          </tr>
        </template>
      </Table>
    </div>
    <Modal title="Formulário de Horário " id="time_modal">
      <form @submit.prevent="handleSubmit(submitAction)">
        <div class="modal-body">
          <div class="mb-3">
            <label for="start_time" class="col-form-label">Horário de Início</label>
            <input type="datetime-local" v-model="start_time" class="form-control" id="start_time" required>
          </div>
          <div class="mb-3">
            <label for="end_time" class="col-form-label">Horário de Saída</label>
            <input type="datetime-local" v-model="end_time" class="form-control" id="end_time" required>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
          <button type="submit" class="btn btn-primary">Enviar</button>
        </div>
      </form>
    </Modal>

  </Page>
</template>


<script>
import Page from './Page.vue';
import axios from '../bootstrap.js';
import Table from './Table.vue';
import Modal from './Modal.vue';

export default {
  name: "Timeslots",
  components: {
    Page,
    Table,
    Modal
  },

  data() {
    return {
      columns: ['ID', 'Início', 'Fim', 'Horas Noturnas', 'Horas Diurnas', 'Ações'],
      hoursSummaryColumns: ['Total de Horas Noturnas', 'Total de Horas Diurnas'],
      rows: [],
      totalDayHours: 0,
      totalNightHours: 0,
      submitAction: 'create',
      start_time: '',
      end_time: '',
      currentId: null


    }
  },

  methods: {

    openModal(action, id = null) {
      if (action === 'create') {
        this.start_time = '';
        this.end_time = '';
        this.submitAction = 'create';
      } else {
        const timeslot = this.rows.find((timeslot) => timeslot.id === id);
        this.start_time = timeslot.start_time;
        this.end_time = timeslot.end_time;
        this.submitAction = 'edit';
        this.currentId = id;
      }

      new bootstrap.Modal(document.getElementById('time_modal')).show();
    },

    mapTableRows(timeslot) {
      const mappedTimeslot = { ...timeslot };
      mappedTimeslot.start_time = new Date(mappedTimeslot.start_time).toLocaleString().slice(0, -3);
      mappedTimeslot.end_time = new Date(mappedTimeslot.end_time).toLocaleString().slice(0, -3);

      mappedTimeslot.night_hours = `${timeslot.night_hours.hours}`.padStart(2, '0') + ':' + `${timeslot.night_hours.minutes.toFixed(0)}`.padStart(2, '0');
      mappedTimeslot.day_hours = `${timeslot.day_hours.hours}`.padStart(2, '0') + ':' + `${timeslot.day_hours.minutes.toFixed(0)}`.padStart(2, '0');
      return mappedTimeslot;
    },

    handleSubmit(action) {
      if (action === 'edit') {
        this.updateTimeslot(this.currentId);
      } else {
        this.createTimeslot();
      }
    },


    fetchTimeslots() {
      axios
        .get('/timeslots')
        .then(response => {
          this.rows = response.data.timeslots;
          this.totalDayHours = response.data.totalDayHours;
          this.totalNightHours = response.data.totalNightHours;

        })
        .catch(error => {
          console.log(error);
        });
    },

    createTimeslot() {
      axios
        .post('/timeslots', {
          start_time: this.start_time,
          end_time: this.end_time
        })
        .then(response => {
          this.$swal.fire({
            icon: 'success',
            title: 'Horário adicionado com sucesso!',
            showConfirmButton: false,
            timer: 1500
          });
          this.start_time = '';
          this.end_time = '';
          const modal = document.getElementById('time_modal');
          const modalInstance = bootstrap.Modal.getInstance(modal);
          modalInstance.hide();

          this.fetchTimeslots();
        })
        .catch(error => {
          if (error.response && error.response.data && error.response.data.error) {
            this.$swal.fire({
              icon: 'error',
              title: 'Oops...',
              text: error.response.data.error,
            });
          } else {
            this.$swal.fire({
              icon: 'error',
              title: 'Oops...',
              text: 'Algo deu errado!',
            });
          }
        });


    },

    updateTimeslot(id) {
      axios
        .put(`/timeslots/${id}`, {
          start_time: this.start_time,
          end_time: this.end_time
        })
        .then(response => {
          this.$swal.fire({
            icon: 'success',
            title: 'Horário atualizado com sucesso!',
            showConfirmButton: false,
            timer: 1500
          });
          this.start_time = '';
          this.end_time = '';
          const modal = document.getElementById('time_modal');
          const modalInstance = bootstrap.Modal.getInstance(modal);
          modalInstance.hide();

          this.fetchTimeslots();
        })
        .catch(error => {
          if (error.response && error.response.data && error.response.data.error) {
            this.$swal.fire({
              icon: 'error',
              title: 'Oops...',
              text: error.response.data.error,
            });
          } else {
            this.$swal.fire({
              icon: 'error',
              title: 'Oops...',
              text: 'Algo deu errado!',
            });
          }
        });
    },

    deleteTimeslot(id) {
      this.$swal.fire({
        title: 'Você tem certeza?',
        text: "Você não poderá reverter isso!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sim, deletar!'
      }).then((result) => {
        if (result.isConfirmed) {
          axios
            .delete(`/timeslots/${id}`)
            .then(response => {
              this.$swal.fire({
                icon: 'success',
                title: 'Horário deletado com sucesso!',
                showConfirmButton: false,
                timer: 1500
              });
              this.fetchTimeslots();
            })
            .catch(error => {
              this.$swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Algo deu errado!',
              });
            });
        }
      });

    },

  },

  mounted() {
    console.log('Component mounted Timer.')
    this.fetchTimeslots();
  }
}
</script>
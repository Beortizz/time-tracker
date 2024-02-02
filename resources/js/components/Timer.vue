<template>
  <Page>
    <Table title="Horários de Trabalho" :columns="columns">
      <template v-slot:addButton>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#time_modal">
          <b><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor"
              class="mr-2 bi bi-plus-circle-fill" viewBox="0 0 16 16">
              <path
                d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM8.5 4.5a.5.5 0 0 0-1 0v3h-3a.5.5 0 0 0 0 1h3v3a.5.5 0 0 0 1 0v-3h3a.5.5 0 0 0 0-1h-3v-3z" />
            </svg> Adicionar</b>
        </button>
      </template>
      <template v-slot:tableBody>
        <tr v-for="row in rows" :key="row.id">
          <td  class="text-center" v-for="(value, key) in row" :key="value">{{ value }}</td>
          <td class="justify-content-center d-flex gap-2">
            <button class="btn btn-success" data-bs-target="#time_modal" data-bs-toggle="modal" @click="editTimeslot(row.id)">Edit</button>
            <button class="btn btn-danger" @click="deleteTimeslot(row.id)">Delete</button>
          </td>
        </tr>
      </template>
    </Table>
    <div class="mt-4">
      <div class="card">
        <div class="card-header d-flex justify-content-between">
          <h3 class="table-title m-0"> Resumo das Horas trabalhadas</h3>
        </div>
        <div class="card-body table-responsive table-sm">
          <div class="d-flex justify-content-sm-start mt-4">
            <table class="w-100 table table-hover dataTableSimple table-striped">
              <thead>
                <tr>
                  <th>Total de Horas Noturnas</th>
                  <th>Total de Horas Diurnas</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>{{ totalNightHours }}</td>
                  <td>{{ totalDayHours }}</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>

    <div class="modal fade" id="time_modal" ref="time_modal" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Horários</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <form @submit.prevent="submitTimeslot">
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
        </div>
      </div>
    </div>
  </Page>
</template>


<script>
import Page from './Page.vue';
import axios from '../bootstrap.js';
import Table from './Table.vue';

export default {
  name: "Timer",
  components: {
    Page,
    Table
  },

  data() {
    return {
      columns: ['ID', 'Início', 'Fim', 'Horas Noturnas', 'Horas Diurnas', 'Ações'],
      rows: [],
      totalDayHours: 0,
      totalNightHours: 0,
      
    }
  },

  methods: {

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

    submitTimeslot() {
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
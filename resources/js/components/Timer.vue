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
                <tr v-for="row in rows" :key="id">
                    <td v-for="(value, key) in row" :key="value">{{ value }}</td>
                </tr>
            </template>
        </Table>
        <div class="modal fade" id="time_modal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title">Horários</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                  <form>
                    <div class="mb-3">
                      <label for="start_time" class="col-form-label">Horário de Início</label>
                      <input type="time" name="end_time" class="form-control" id="start_time">
                    </div>
                    <div class="mb-3">
                      <label for="end_time" class="col-form-label">Horário de Saída</label>
                      <input type="time" name="end_time" class="form-control" id="end_time">
                    </div>
                  </form>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                  <button type="button" class="btn btn-primary">Enviar</button>
                </div>
              </div>
            </div>
          </div>
    </Page>
</template>


<script>
import Page from './page.vue';
import Table from './Table.vue';

export default {
    name: "Timer",
    components: {
        Page,
        Table
    },

    data() {
        return {
            columns: ['Início', 'Fim'],
            rows: [
                { start: '08:00', end: '12:00' },
                { start: '13:00', end: '17:00' },
                { start: '08:00', end: '12:00' },
                { start: '13:00', end: '17:00' },
            ]
        }
    },

    methods: {

    },

    mounted() {
        console.log('Component mounted Timer.')
    }
}
</script>
<template>
    <div>
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <h3 class="table-title m-0">{{ title }}</h3>
                <div class="input-group input-group-sm w-auto">
                    <slot name="addButton">

                    </slot>
                </div>
            </div>
            <div class="card-body table-responsive table-sm">
                <table class="w-100 table table-hover dataTableSimple table-striped">
                    <thead>
                        <tr>
                            <th v-for="column in columns" :key="column">{{ column }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        <slot name="tableBody"></slot>
                    </tbody>
                </table>
                <div class="d-flex justify-content-sm-start mt-4">
                </div>
            </div>
        </div>
    </div>
</template>
<script>


export default {
    name: "Table",
    props: {
        title: {
            type: String,
            required: true
        },
        columns: {
            type: Array,
            required: false
        },


    },

    mounted() {
        console.log("Component mounted.");
    }
};
</script>
  
  
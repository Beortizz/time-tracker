<template>
    <div class="container-fluid">
        <div class="row flex-nowrap">
            <Sidebar v-if="!hideSidebar" />
            <div class="col py-3">
                <slot/>
            </div>
        </div>
    </div>
</template>

<script>
import Sidebar from "./sidebar.vue";
export default {
    name: "Page",
    components: {
        Sidebar
    },
    props : {
        hideSidebar: {
            type: Boolean,
            default: false,
            required: false
        }
    },

}
</script>

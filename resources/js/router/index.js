import { createRouter, createWebHistory } from "vue-router";
import Timeslots from '../components/Timeslots.vue'

const routes = [
    {
        path: "/timeslots",
        name: "Timeslots",
        component: Timeslots,
    },

    {
        path: "/",
        redirect: "/timeslots",
    },

];

const router = createRouter({
    history: createWebHistory(),
    routes
});


export default router;
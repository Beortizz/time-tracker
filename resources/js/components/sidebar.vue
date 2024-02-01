<template>
    <div class="col-auto col-md-3 col-xl-2 px-sm-2 px-0 bg-dark">
        <div class="d-flex flex-column align-items-center align-items-sm-start px-3 pt-2 text-white min-vh-100">
            <a href="/" class="d-flex align-items-center pb-3 mb-md-0 me-md-auto text-white text-decoration-none">
                <span class="fs-5 d-none d-sm-inline">Academia do Gordão</span>
            </a>
            <ul class="nav nav-pills flex-column mb-sm-auto mb-0 align-items-center align-items-sm-start" id="menu">
                <li class="nav-item">
                    <a href="/" class="nav-link align-middle px-0">
                        <span class="material-symbols-outlined">
                            home
                        </span>
                        <span class="ms-1 d-none d-sm-inline">Home</span>
                    </a>
                </li>
                <li>
                    <a href="/students" class="nav-link px-0 align-middle">
                        <span class="material-symbols-outlined">
                            group
                        </span>
                        <span class="ms-2 d-none d-sm-inline">Alunos</span>
                    </a>
                </li>
                <li>
                    <a href="/exercises" class="nav-link px-0 align-middle">
                        <span class="material-symbols-outlined">
                            fitness_center
                        </span>
                        <span class="ms-2 d-none d-sm-inline">Exercícios</span>
                    </a>
                </li>
            </ul>
            <hr>
            <button type="button" class="btn btn-outline-light btn-sm" @click="logout">
                <span class="material-symbols-outlined">
                    logout
                </span>
                <span class="ms-2 d-none d-sm-inline">Sair</span>
            </button>
        </div>
    </div>
</template>

<script>
export default {
    name: "Sidebar",

    mounted() {
        console.log('Component mounted Sidebar.')
    },

    // methods: {
    //     logout() {
    //         axios.post('/logout')
    //             .then(response => {
    //                 if (response.data.status === 'success') {
    //                     window.localStorage.removeItem('token');
    //                     this.$router.push({ name: 'login' });
    //                 } else {
    //                     // Tratar caso a resposta não seja de sucesso
    //                     console.error(response.data.message);
    //                 }
    //             })
    //             .catch(error => {
    //                 // Tratar erros de requisição
    //                 console.error(error);
    //             });
    //     }

    // }
}
</script>

<style scoped>
#menu {
    width: 100%;
}

#menu li {
    font-size: 1.5rem;
}
</style>
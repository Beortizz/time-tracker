<template>
    <div class="container-fluid">
        <div class="row flex-nowrap">
            <div class="col py-3">
                <slot/>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "Page",
  
    mounted() {
        console.log("Component mounted.");
    }
}
</script>

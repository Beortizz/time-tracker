<template>
    <div class="modal fade" :id="id"  tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ title }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <slot name="modalBody"> </slot>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "Modal",
    props: {
        title: {
            type: String,
            required: true
        },
        id: {
            type: String,
            required: true
        }

    },
}
</script>
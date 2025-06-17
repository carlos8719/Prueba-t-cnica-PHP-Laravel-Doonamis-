<template>
    <div class="modal-overlay" @click.self="emit('close')">
        <div class="modal-content">
        <h3 class="modal-title">{{ note.title }}</h3>
        <p class="modal-body">{{ note.content }}</p>
        <button @click="emit('close')" class="btn-close btn-close-view">Cerrar</button>
        </div>
    </div>
</template>

<script setup>
    defineProps({
        note: Object
    })
    const emit = defineEmits(['close'])
</script>
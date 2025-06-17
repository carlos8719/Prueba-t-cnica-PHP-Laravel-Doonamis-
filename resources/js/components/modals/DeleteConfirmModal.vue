<template>
    <div class="modal-overlay" @click.self="emit('cancel')">
        <div class="modal-content">
        <h2 class="modal-title mb-6">¿Estás seguro de eliminar esta nota?</h2>
        <div class="modal-delete-buttons">
            <button @click="emit('confirm')" class="btn-delete btn-modal">🗑️ Sí, eliminar</button>
            <button @click="emit('cancel')" class="btn-edit btn-modal">✏️ Cancelar</button>
        </div>
        </div>
    </div>
</template>

<script setup>
    const emit = defineEmits(['confirm', 'cancel'])
</script>
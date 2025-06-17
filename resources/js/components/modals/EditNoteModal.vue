<template>
    <div class="modal-overlay" @click.self="emit('close')">
        <div class="modal-content">
        <h3 class="modal-title">✏️ Editar Nota</h3>
        <form @submit.prevent="() => emit('submit', note)" class="form-edit">
            <input v-model="note.title" type="text" placeholder="Título" required class="form-input" />
            <textarea v-model="note.content" placeholder="Contenido" required class="form-textarea"></textarea>
            <div class="form-buttons">
            <button type="submit" class="btn-save">Guardar</button>
            <button type="button" @click="emit('close')" class="btn-cancel">Cancelar</button>
            </div>
        </form>
        </div>
    </div>
</template>

<script setup>
    defineProps({
        note: Object
    })
    const emit = defineEmits(['close', 'submit'])
</script>
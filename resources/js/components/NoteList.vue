<template>
  <div class="container">
    <h1 class="title">📝 Mis Notas</h1>

    <div class="search-wrapper">
      <input
        v-model="searchTitle"
        @keyup.enter="searchNoteByTitle"
        type="text"
        placeholder="Buscar por título"
        class="search-input"
      />
      <button @click="searchNoteByTitle" class="search-button" aria-label="Buscar">
        🔍
      </button>
    </div>

    <button @click="openCreateModal" class="btn-create" aria-label="Crear nota nueva">+</button>

    <div class="notes-grid">
      <div v-for="note in notes" :key="note.id" class="note-card">
        <div>
          <h2 class="note-title">{{ note.title }}</h2>
          <p class="note-content">{{ note.content }}</p>
        </div>
        <div class="note-actions">
          <button @click="openViewModal(note)" title="Ver" class="btn-view">📖</button>
          <button @click="openEditModal(note)" title="Editar" class="btn-edit">✏️</button>
          <button @click="deleteNote(note.id)" title="Borrar" class="btn-delete">🗑️</button>
        </div>
      </div>
    </div>

    <div class="pagination">
      <button
        @click="goToPreviousPage"
        :disabled="currentPage === 1"
        class="page-btn"
        aria-label="Página anterior"
      >
        ←
      </button>
      <span class="page-info">Página {{ currentPage }} de {{ lastPage }}</span>
      <button
        @click="goToNextPage"
        :disabled="currentPage === lastPage"
        class="page-btn"
        aria-label="Página siguiente"
      >
        →
      </button>
    </div>

    <ViewNoteModal
      v-if="showView"
      :note="selectedNote"
      @close="closeModal"
    />

    <EditNoteModal
      v-if="showEdit"
      :note="editNote"
      @update="updateNote"
      @close="closeModal"
    />

    <CreateNoteModal
      v-if="showCreate"
      :note="newNote"
      @create="createNote"
      @close="closeModal"
    />

    <DeleteConfirmModal
      v-if="showDeleteConfirm"
      @confirm="confirmDeleteNote"
      @cancel="cancelDelete"
    />
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import {
  getNotes,
  searchNotesByTitle,
  createNote as apiCreateNote,
  updateNote as apiUpdateNote,
  deleteNote as apiDeleteNote
} from '@/services/noteService'

import ViewNoteModal from './modals/ViewNoteModal.vue'
import EditNoteModal from './modals/EditNoteModal.vue'
import CreateNoteModal from './modals/CreateNoteModal.vue'
import DeleteConfirmModal from './modals/DeleteConfirmModal.vue'

const notes = ref([])
const currentPage = ref(1)
const lastPage = ref(1)
const searchTitle = ref('')
const showCreate = ref(false)
const newNote = ref({ title: '', content: '' })
const selectedNote = ref({})
const editNote = ref({ id: null, title: '', content: '' })
const showView = ref(false)
const showEdit = ref(false)
const showDeleteConfirm = ref(false)
const noteToDeleteId = ref(null)

const fetchNotes = async (page = 1) => {
  try {
    const response = await getNotes(page)
    notes.value = response.data.data
    currentPage.value = response.data.current_page
    lastPage.value = response.data.last_page
  } catch (error) {
    console.error('Error fetching notes:', error)
  }
}

const goToPreviousPage = () => {
  if (currentPage.value > 1) {
    fetchNotes(currentPage.value - 1)
  }
}

const goToNextPage = () => {
  if (currentPage.value < lastPage.value) {
    fetchNotes(currentPage.value + 1)
  }
}

const searchNoteByTitle = async () => {
  if (!searchTitle.value.trim()) {
    await fetchNotes(1)
    return
  }

  try {
    const response = await searchNotesByTitle(searchTitle.value)
    const result = response.data

    if (!result || (Array.isArray(result) && result.length === 0)) {
      await fetchNotes(1)
      return
    }
    notes.value = Array.isArray(result) ? result : [result]
    lastPage.value = 1
    currentPage.value = 1
  } catch (error) {
    await fetchNotes(1)
  }
}

const openViewModal = (note) => {
  selectedNote.value = note
  showView.value = true
}

const openEditModal = (note) => {
  editNote.value = { ...note }
  showEdit.value = true
}

const closeModal = () => {
  showView.value = false
  showEdit.value = false
  showDeleteConfirm.value = false
  showCreate.value = false
}

const updateNote = async (updatedNote) => {
  try {
    await apiUpdateNote(updatedNote.id, updatedNote)
    await fetchNotes(currentPage.value)
    closeModal()
  } catch (error) {
    console.error('Error updating note:', error)
  }
}

const deleteNote = (id) => {
  noteToDeleteId.value = id
  showDeleteConfirm.value = true
}

const confirmDeleteNote = async () => {
  try {
    await apiDeleteNote(noteToDeleteId.value)
    searchTitle.value = ''
    await fetchNotes(currentPage.value)
    showDeleteConfirm.value = false
    noteToDeleteId.value = null
  } catch (error) {
    console.error('Error al eliminar la nota:', error)
  }
}

const cancelDelete = () => {
  showDeleteConfirm.value = false
  noteToDeleteId.value = null
}

const openCreateModal = () => {
  newNote.value = { title: '', content: '' }
  showCreate.value = true
}

const createNote = async (note) => {
  try {
    await apiCreateNote(note)
    await fetchNotes(currentPage.value)
    closeModal()
  } catch (error) {
    console.error('Error al crear nota:', error)
  }
}

onMounted(() => fetchNotes())
</script>
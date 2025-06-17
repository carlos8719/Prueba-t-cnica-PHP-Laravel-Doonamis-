
import axios from 'axios'

const API_HEADERS = {
    headers: {
        Authentication: 'Doonamis'
    }
}

export const getNotes = (page = 1) =>
    axios.get(`/api/notes?page=${page}`, API_HEADERS)

export const searchNotesByTitle = (title) =>
    axios.get(`/api/note/title/${title}`, API_HEADERS)

export const createNote = (data) =>
    axios.post('/api/notes', data, API_HEADERS)

export const updateNote = (id, data) =>
    axios.put(`/api/notes/${id}`, data, API_HEADERS)

export const deleteNote = (id) =>
    axios.delete(`/api/notes/${id}`, API_HEADERS)

<?php

namespace App\Http\Controllers;

use App\Models\Note;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class NoteController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(Note::orderBy('id', 'desc')->paginate(6));
    }

    public function show(int $id): JsonResponse
    {
        $note = Note::find($id);

        if (!$note) {
            return response()->json(['error' => 'Nota no encontrada'], 404);
        }

        return response()->json($note);
    }

    public function store(Request $request): JsonResponse
    {
        $note = Note::createFromRequest($request);
        return response()->json($note, 201);
    }

    public function findByTitle(string $title): JsonResponse
    {
        $notes = Note::searchByTitle($title);
        return response()->json($notes);
    }

    public function update(Request $request, Note $note): JsonResponse
    {
        try {
            $note->updateFromRequest($request);
            return response()->json($note);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'No se pudo actualizar la nota',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy(Note $note): Response|JsonResponse
    {
        try {
            $note->delete();
            return response()->noContent();
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error al eliminar la nota',
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}

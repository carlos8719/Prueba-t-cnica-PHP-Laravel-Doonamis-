<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Collection;

class Note extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'content'];

    public static function searchByTitle(string $title): Collection
    {
        return self::where('title', 'like', "%{$title}%")->get();
    }

    public static function createFromRequest(Request $request): self
    {
        $validated = self::validateData($request);
        return self::create($validated);
    }

    public function updateFromRequest(Request $request): bool
    {
        $validated = self::validateData($request);
        return $this->update($validated);
    }

    public static function validateData(Request $request): array
    {
        return $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);
    }
}

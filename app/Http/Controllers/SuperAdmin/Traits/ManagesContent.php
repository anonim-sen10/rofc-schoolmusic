<?php

namespace App\Http\Controllers\SuperAdmin\Traits;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

trait ManagesContent
{
    public function storeContent(Request $request, string $module): RedirectResponse
    {
        $data = $this->validateContentPayload($request, $module);

        DB::table($this->contentTable($module))->insert(array_merge($data, [
            'created_at' => now(),
            'updated_at' => now(),
        ]));

        return back()->with('success', 'Data ' . $module . ' berhasil ditambahkan.');
    }

    public function updateContent(Request $request, string $module, int $id): RedirectResponse
    {
        $data = $this->validateContentPayload($request, $module, $id);

        DB::table($this->contentTable($module))
            ->where('id', $id)
            ->update(array_merge($data, ['updated_at' => now()]));

        return back()->with('success', 'Data ' . $module . ' berhasil diperbarui.');
    }

    public function destroyContent(string $module, int $id): RedirectResponse
    {
        DB::table($this->contentTable($module))->where('id', $id)->delete();

        return back()->with('success', 'Data ' . $module . ' berhasil dihapus.');
    }

    public function destroyLog(int $id): RedirectResponse
    {
        DB::table('activities')->where('id', $id)->delete();

        return back()->with('success', 'Log aktivitas berhasil dihapus.');
    }

    private function contentTable(string $module): string
    {
        return match ($module) {
            'blog' => 'posts',
            'gallery' => 'galleries',
            'events' => 'events',
            'testimonials' => 'testimonials',
            'settings' => 'settings',
            default => abort(404),
        };
    }

    private function validateContentPayload(Request $request, string $module, ?int $id = null): array
    {
        return match ($module) {
            'blog' => $request->validate([
                'title' => ['required', 'string', 'max:255'],
                'slug' => ['required', 'string', 'max:255', Rule::unique('posts', 'slug')->ignore($id)],
                'excerpt' => ['nullable', 'string'],
                'content' => ['nullable', 'string'],
                'cover_image' => ['nullable', 'string', 'max:255'],
                'status' => ['required', 'in:draft,published'],
                'published_at' => ['nullable', 'date'],
            ]),
            'gallery' => $request->validate([
                'title' => ['required', 'string', 'max:255'],
                'category' => ['nullable', 'string', 'max:255'],
                'type' => ['required', 'in:photo,video'],
                'file_path' => ['required', 'string', 'max:255'],
            ]),
            'events' => $request->validate([
                'title' => ['required', 'string', 'max:255'],
                'description' => ['nullable', 'string'],
                'event_date' => ['nullable', 'date'],
                'location' => ['nullable', 'string', 'max:255'],
                'status' => ['required', 'in:draft,upcoming,completed,cancelled'],
            ]),
            'testimonials' => $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'role' => ['nullable', 'string', 'max:255'],
                'message' => ['required', 'string'],
                'is_published' => ['required', 'in:1,0'],
            ]),
            'settings' => $request->validate([
                'key' => ['required', 'string', 'max:255', Rule::unique('settings', 'key')->ignore($id)],
                'value' => ['nullable', 'string'],
            ]),
            default => abort(404),
        };
    }
}

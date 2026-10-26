<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AcademyGalleryItem;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class GalleryController extends Controller
{
    public function index(): View
    {
        $items = AcademyGalleryItem::query()
            ->orderBy('sort_order')
            ->orderByDesc('created_at')
            ->paginate(12);

        return view('admin.gallery.index', compact('items'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            // JFIF is normally detected as image/jpeg, so it is accepted regardless of its extension.
            // SVG is intentionally excluded because it can contain active content when served publicly.
            'image' => ['required', 'file', 'mimetypes:image/jpeg,image/pjpeg,image/png,image/gif,image/webp,image/avif,image/bmp,image/x-ms-bmp,image/tiff', 'max:5120'],
            'caption' => ['nullable', 'string', 'max:180'],
        ]);

        $path = $request->file('image')->store('academy-gallery', 'public');

        AcademyGalleryItem::create([
            'image_path' => $path,
            'caption' => $data['caption'] ?? null,
        ]);

        return redirect()->route('admin.gallery.index')->with('status', 'Photo ajoutée à la galerie.');
    }

    public function destroy(AcademyGalleryItem $item): RedirectResponse
    {
        Storage::disk('public')->delete($item->image_path);
        $item->delete();

        return redirect()->route('admin.gallery.index')->with('status', 'Photo supprimée de la galerie.');
    }
}

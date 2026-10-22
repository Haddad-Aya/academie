@extends('layouts.academie')
@section('title', 'Galerie du site')
@section('plain', '1')

@section('content')
<div class="admin">
  @include('admin._sidebar')

  <main class="main">
    <h1 class="h">Galerie du site</h1>
    @include('partials.flash')

    <section class="card" style="max-width:760px">
      <h2 class="h section-title" style="margin:0 0 12px">Ajouter une photo</h2>
      <p class="note" style="text-align:start;margin:0 0 14px">La photo sera visible publiquement sur la page d’accueil. Pour les mineurs, vérifiez que l’académie possède l’autorisation de publication du représentant légal.</p>

      <form method="POST" action="{{ route('admin.gallery.store') }}" enctype="multipart/form-data" style="display:flex;flex-direction:column;gap:14px">
        @csrf
        <div class="field">
          <label for="image">Image (JPG/JPEG/JFIF, PNG, GIF, WebP, AVIF, BMP ou TIFF — 5 Mo maximum)</label>
          <input id="image" name="image" type="file" accept="image/*,.jfif,.tif,.tiff,.bmp,.avif" required>
          @error('image') <span class="err">{{ $message }}</span> @enderror
        </div>
        <div class="field">
          <label for="caption">Légende (facultative)</label>
          <input id="caption" name="caption" maxlength="180" value="{{ old('caption') }}" placeholder="Ex. Entraînement U11">
          @error('caption') <span class="err">{{ $message }}</span> @enderror
        </div>
        <div><button class="btn btn-primary" type="submit">Ajouter à la galerie</button></div>
      </form>
    </section>

    <section class="cards">
      @forelse ($items as $item)
        <article class="card" style="padding:12px">
          <img src="{{ asset('storage/' . $item->image_path) }}" alt="{{ $item->caption ?: 'Photo de la galerie' }}" style="width:100%;aspect-ratio:4/3;object-fit:cover;border-radius:12px">
          <p style="margin:10px 0">{{ $item->caption ?: 'Sans légende' }}</p>
          <form method="POST" action="{{ route('admin.gallery.destroy', $item) }}" onsubmit="return confirm('Supprimer cette photo de la galerie ?')">
            @csrf
            @method('DELETE')
            <button class="btn btn-danger btn-sm" type="submit">Supprimer</button>
          </form>
        </article>
      @empty
        <div class="card muted">Aucune photo dans la galerie pour le moment.</div>
      @endforelse
    </section>
    <div class="pager" style="margin-top:14px">{{ $items->links() }}</div>
  </main>
</div>
@endsection

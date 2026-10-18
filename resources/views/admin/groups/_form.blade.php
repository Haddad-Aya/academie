{{-- Variables : $action, $method (null|'PUT'), $group (null à la création), $coaches --}}
<form method="POST" action="{{ $action }}" class="card" style="display:flex;flex-direction:column;gap:16px;max-width:760px">
  @csrf
  @if (! empty($method)) @method($method) @endif

  <div class="form-grid">
    <div class="field"><label for="name">Nom du groupe</label>
      <input id="name" name="name" value="{{ old('name', $group?->name) }}" placeholder="U9 · Initiation" required></div>
    <div class="field"><label for="category">Catégorie</label>
      <input id="category" name="category" value="{{ old('category', $group?->category) }}" placeholder="U9" maxlength="10" required></div>
    <div class="field"><label for="age_min">Âge minimum</label>
      <input id="age_min" name="age_min" type="number" min="3" max="25" value="{{ old('age_min', $group?->age_min) }}" required></div>
    <div class="field"><label for="age_max">Âge maximum</label>
      <input id="age_max" name="age_max" type="number" min="3" max="25" value="{{ old('age_max', $group?->age_max) }}" required></div>
    <div class="field"><label for="schedule">Horaires</label>
      <input id="schedule" name="schedule" value="{{ old('schedule', $group?->schedule) }}" placeholder="Mer & Sam · 16h00"></div>
    <div class="field"><label for="location">Lieu</label>
      <input id="location" name="location" value="{{ old('location', $group?->location) }}" placeholder="Stade municipal"></div>
    <div class="field"><label for="coach_id">Entraîneur</label>
      <select id="coach_id" name="coach_id">
        <option value="">— Aucun —</option>
        @foreach ($coaches as $c)
          <option value="{{ $c->id }}" @selected((int) old('coach_id', $group?->coach_id) === $c->id)>{{ $c->name }}</option>
        @endforeach
      </select></div>
    <div class="field"><label for="capacity">Places</label>
      <input id="capacity" name="capacity" type="number" min="1" max="200" value="{{ old('capacity', $group?->capacity ?? 25) }}" required></div>
    <div class="field"><label for="price">Cotisation mensuelle (DT / mois)</label>
      <input id="price" name="price" type="number" step="0.001" min="0" value="{{ old('price', $group?->price ?? 0) }}" required></div>
  </div>

  {{-- La valeur cachée "0" permet de désactiver un groupe : une case décochée n'envoie rien. --}}
  <label class="check">
    <input type="hidden" name="active" value="0">
    <input type="checkbox" name="active" value="1" @checked(old('active', $group?->active ?? true))>
    <span>Groupe actif (visible pour les inscriptions)</span>
  </label>

  <div class="actions">
    <button class="btn btn-primary" type="submit">Enregistrer</button>
    <a class="btn btn-outline" href="{{ route('admin.groups.index') }}">Annuler</a>
  </div>
</form>

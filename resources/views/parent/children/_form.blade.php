{{-- Formulaire commun création / modification d'un enfant.
     Variables : $action (URL), $method (null ou 'PUT'), $child (null à la création) --}}
<form method="POST" action="{{ $action }}" class="content" id="childForm">
  @csrf
  @if (! empty($method))
    @method($method)
  @endif

  @include('partials.flash')

  <div class="field">
    <label for="first_name" data-i18n="f_first">Prénom</label>
    <input id="first_name" name="first_name" type="text" value="{{ old('first_name', $child?->first_name) }}" required>
  </div>
  <div class="field">
    <label for="last_name" data-i18n="f_last">Nom</label>
    <input id="last_name" name="last_name" type="text" value="{{ old('last_name', $child?->last_name) }}" required>
  </div>
  <div class="field">
    <label for="birth_date" data-i18n="f_birth">Date de naissance</label>
    <input id="birth_date" name="birth_date" type="date" class="ltr" value="{{ old('birth_date', $child?->birth_date?->format('Y-m-d')) }}" required>
  </div>
  <div class="field">
    <label for="medical_notes" data-i18n="f_medical">Informations médicales utiles (allergies, asthme…)</label>
    <textarea id="medical_notes" name="medical_notes" rows="3" maxlength="1000">{{ old('medical_notes', $child?->medical_notes) }}</textarea>
  </div>
  <label class="check">
    <input type="checkbox" name="photo_consent" value="1" @checked(old('photo_consent', $child?->photo_consent))>
    <span data-i18n="f_photo">J'autorise la publication de photos de mon enfant</span>
  </label>
</form>

<div class="footbar">
  <button class="btn btn-primary btn-block" type="submit" form="childForm" data-i18n="save">Enregistrer</button>
</div>

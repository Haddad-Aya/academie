{{-- Tableau des inscriptions. Variable : $rows --}}
<div class="table-wrap">
  <table>
    <thead>
      <tr><th>Enfant</th><th>Parent</th><th>Groupe</th><th>Statut</th><th>Paiement</th><th>Actions</th></tr>
    </thead>
    <tbody>
      @forelse ($rows as $r)
        <tr>
          <td><strong>{{ $r->child->full_name }}</strong><br><span class="muted">{{ $r->child->age }} ans</span></td>
          <td>{{ $r->child->parent->name }}<br><span class="muted">{{ $r->child->parent->email }}</span></td>
          <td>{{ $r->trainingGroup->name }}</td>
          <td>
            @if ($r->status === 'approved') <span class="badge badge-ok">Validée</span>
            @elseif ($r->status === 'pending') <span class="badge badge-wait">À valider</span>
            @else <span class="badge badge-off">Refusée</span> @endif
          </td>
          <td>
            @if ($r->payments->contains('status', 'paid')) <span class="badge badge-ok">Payé</span>
            @elseif ($r->payments->isEmpty()) <span class="muted">—</span>
            @else <span class="badge badge-wait">En attente</span> @endif
          </td>
          <td>
            @if ($r->status === 'pending')
              <div style="display:flex;gap:8px">
                <form method="POST" action="{{ route('admin.registrations.approve', $r) }}">@csrf
                  <button class="btn btn-primary btn-sm" type="submit">Valider</button></form>
                <form method="POST" action="{{ route('admin.registrations.reject', $r) }}">@csrf
                  <button class="btn btn-outline btn-sm" type="submit">Refuser</button></form>
              </div>
            @endif
          </td>
        </tr>
      @empty
        <tr><td colspan="6" class="muted">Aucune inscription.</td></tr>
      @endforelse
    </tbody>
  </table>
</div>

@if (session('status'))
  <div class="flash flash-ok" role="status">{{ session('status') }}</div>
@endif
@if ($errors->any())
  <div class="flash flash-err" role="alert">
    <ul>
      @foreach ($errors->all() as $message)
        <li>{{ $message }}</li>
      @endforeach
    </ul>
  </div>
@endif

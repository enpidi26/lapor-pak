@props(['user', 'size' => null])

<span class="avatar {{ $size ? 'avatar--'.$size : '' }}">
    @if ($user->foto)
        <img src="{{ $user->fotoUrl() }}" alt="Foto {{ $user->name }}" loading="lazy">
    @else
        {{ $user->inisial() }}
    @endif
</span>

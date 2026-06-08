@php
    use Illuminate\Support\Str;
    $id = Str::slug($titlegroup);
@endphp

<li class="nav-item nav-parent-menu menu-group">
    <a class="nav-link" href="#{{ $id }}" data-bs-toggle="collapse" role="button" aria-expanded="false"
        aria-controls="{{ $id }}">
        <i class="{{ $icon ?? 'iconoir-compact-disc' }} menu-icon"></i>
        <span><small>{{ $titlegroup }}</small></span>
    </a>

    <div class="collapse" id="{{ $id }}">
        <ul class="nav flex-column parent-item">
            @foreach ($menu as $item)
                <x-backend.nav.sidebar.menu url="{{ $item['url'] }}" key="{{ $item['key'] }}"
                    icon="{{ $item['icon'] }}" :authority="$authority" :badge="$item['badge'] ?? 0" />
            @endforeach
        </ul>
    </div>
</li>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const parentGroup = document.getElementById('{{ $id }}');
        if (!parentGroup) return;

        const items = parentGroup.querySelectorAll('.parent-item .nav-item-menu');

        if (items.length === 0) {
            parentGroup.closest('.menu-group')?.remove();
        }
    });
</script>

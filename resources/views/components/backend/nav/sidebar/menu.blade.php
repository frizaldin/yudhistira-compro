@foreach ($feature as $e)
    @if (in_array($e->id, json_decode($authority->code, true)))
        <li class="nav-item nav-item-menu @if (Route::currentRouteName() == $key) active @endif">
            <a href="{{ $url }}" class="nav-link">
                <i class="{{ $icon ? $icon : 'iconoir-report-columns' }} menu-icon"></i>
                <span><small>{{ $e->title }}</small></span>
                @if (isset($badge) && $badge > 0)
                    <span class="badge text-bg-danger ms-auto" style="margin-left:8px;">{{ $badge }}</span>
                @endif
            </a>
        </li>
    @endif
@endforeach

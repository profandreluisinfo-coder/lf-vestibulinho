@if (!empty($items))
    <nav class="admin-breadcrumb px-3 px-md-4 pt-4" aria-label="Navegação estrutural">
        <ol>
            @foreach ($items as $item)
                <li>
                    @if (!empty($item['url']) && !$loop->last)
                        <a href="{{ $item['url'] }}">{{ $item['label'] }}</a>
                    @else
                        <span aria-current="page">{{ $item['label'] }}</span>
                    @endif
                </li>
            @endforeach
        </ol>
    </nav>
@endif

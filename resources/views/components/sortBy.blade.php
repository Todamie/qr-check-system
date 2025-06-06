@props(['sortBy', 'sortOrder', 'page', 'target', 'name', 'params' => []])

@php
    $queryString = http_build_query(array_merge($params, [
        'sortBy' => $sortBy,
        'sortOrder' => $sortOrder == 'asc' ? 'desc' : 'asc',
        'page' => $page,
    ]));
@endphp

<a href="{{ url($target . '?' . $queryString) }}">

    <?php
    $arrow = '';
    $requestSortBy = request()->sortBy;
    ?>

    @if ($sortBy == $requestSortBy)
        @if ($sortOrder == 'asc')
            <?php
            $arrow = asset('arrow-down.svg');
            ?>
        @else
            <?php
            $arrow = asset('arrow-up.svg');
            ?>
        @endif
    @endif

    <span style="display: inline-flex; align-items: center; text-decoration: underline !important;">
        {!! trim($slot) !!}
        @if ($arrow)
            <img src="{{ $arrow }}" alt="arrow" style="margin-left: 4px; height: 1em;">
        @endif
    </span>

</a>

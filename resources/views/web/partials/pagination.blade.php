@if ($paginator->hasPages())
    <div class="pagination">
        @if ($paginator->onFirstPage())
            <a href="{{ $paginator->previousPageUrl() }}" class="disabled">
                <svg xmlns="http://www.w3.org/2000/svg" width="34" height="31" viewBox="0 0 48 38">
                    <g transform="translate(-877.5 -600)">
                        <path d="M-19057.816-16469.5l-5,5,5,5" transform="translate(19961.816 17083)" stroke="#F1641E"
                              stroke-width="1" fill="none"></path>
                    </g>
                </svg>
            </a>
        @else
            <a href="{{ $paginator->previousPageUrl() }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="34" height="31" viewBox="0 0 48 38">
                    <g transform="translate(-877.5 -600)">
                        <path d="M-19057.816-16469.5l-5,5,5,5" transform="translate(19961.816 17083)" stroke="#F1641E"
                              stroke-width="1" fill="none"></path>
                    </g>
                </svg>
            </a>
        @endif

        <ol>
            @foreach ($elements as $element)
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li><a class="this">{{ $page }}</a></li>
                        @else
                            <li><a href="{{ $url }}">{{ $page }}</a></li>
                        @endif
                    @endforeach
                @endif
            @endforeach
        </ol>

        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="34" height="31" viewBox="0 0 48 38">
                    <g transform="translate(-540 -859)">
                        <path d="M-19062.814-16469.5l5,5-5,5" transform="translate(19624.314 17342)" stroke="#F1641E"
                              stroke-width="1" fill="none"></path>
                    </g>
                </svg>
            </a>
        @else
            <a class="disabled">
                <svg xmlns="http://www.w3.org/2000/svg" width="34" height="31" viewBox="0 0 48 38">
                    <g transform="translate(-540 -859)">
                        <path d="M-19062.814-16469.5l5,5-5,5" transform="translate(19624.314 17342)" stroke="#F1641E"
                              stroke-width="1" fill="none"></path>
                    </g>
                </svg>
            </a>
        @endif
    </div>
@endif

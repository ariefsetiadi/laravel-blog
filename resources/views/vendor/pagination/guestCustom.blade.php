@if($paginator->hasPages())
  <ul id="pagination">
    @if($paginator->onFirstPage())
      <li>
        <a>
          <i class="bi bi-chevron-left"></i>
        </a>
      </li>
    @else
      <li>
        <a href="{{ $paginator->previousPageUrl() }}" rel="prev">
          <i class="bi bi-chevron-left"></i>
        </a>
      </li>
    @endif

    @foreach($elements as $element)
      @if(is_string($element))
        <li>{{ $element }}</li>
      @endif

      @if(is_array($element))
        @foreach ($element as $page => $url)
          @if ($page == $paginator->currentPage())
            <li>
              <a class="active">{{ $page }}</a>
            </li>
          @else
            <li>
              <a href="{{ $url }}">{{ $page }}</a>
            </li>
          @endif
        @endforeach
      @endif
    @endforeach

    @if ($paginator->hasMorePages())
      <li>
        <a href="{{ $paginator->nextPageUrl() }}" rel="next">
          <i class="bi bi-chevron-right"></i>
        </a>
      </li>
    @else
      <li>
        <a>
          <i class="bi bi-chevron-right"></i>
        </a>
      </li>
    @endif
  </ul>
@endif
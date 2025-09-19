@if ($paginator->hasPages())
<div class="row">
  <div class="col-sm-5">
    <div class="dataTables_info" id="DataTables_Table_0_info" role="status" aria-live="polite">Showing 1 to 10 of 57 entries</div>
  </div>
  <div class="col-sm-7">
    <div class="dataTables_paginate paging_simple_numbers" id="DataTables_Table_0_paginate" style="float:right;">
      <ul class="pagination">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
        <li class="paginate_button previous disabled" id="DataTables_Table_0_previous"><a href="#" aria-controls="DataTables_Table_0" data-dt-idx="0" tabindex="0">Previous</a></li>
        @else
        <li class="paginate_button active"><a href="#" aria-controls="DataTables_Table_0" data-dt-idx="1" tabindex="0">1</a></li>
        @endif
        <li class="paginate_button "><a href="#" aria-controls="DataTables_Table_0" data-dt-idx="2" tabindex="0">2</a></li>
        <li class="paginate_button "><a href="#" aria-controls="DataTables_Table_0" data-dt-idx="3" tabindex="0">3</a></li>
        <li class="paginate_button "><a href="#" aria-controls="DataTables_Table_0" data-dt-idx="4" tabindex="0">4</a></li>
        <li class="paginate_button "><a href="#" aria-controls="DataTables_Table_0" data-dt-idx="5" tabindex="0">5</a></li>
        <li class="paginate_button "><a href="#" aria-controls="DataTables_Table_0" data-dt-idx="6" tabindex="0">6</a></li>
        <li class="paginate_button next" id="DataTables_Table_0_next"><a href="#" aria-controls="DataTables_Table_0" data-dt-idx="7" tabindex="0">Next</a></li>
      </ul>
    </div>
  </div>
</div>
@endif 



@if ($paginator->hasPages())
<div class="row">
  <div class="col-sm-5">
    <div class="dataTables_info" id="DataTables_Table_0_info" role="status" aria-live="polite">Showing 1 to 10 of 57 entries</div>
  </div>
  <div class="col-sm-7">
    <div class="dataTables_paginate paging_simple_numbers" id="DataTables_Table_0_paginate" style="float:right;">
      <ul class="pagination">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
        	<li class="paginate_button disabled" aria-disabled="true" aria-label="@lang('pagination.previous')"> <span aria-hidden="true">&lsaquo;</span> </li>
        @else
        	<li class="paginate_button"> <a href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="@lang('pagination.previous')">&lsaquo;</a> </li>
        @endif
        
        {{-- Pagination Elements --}}
        @foreach ($elements as $element)
        {{-- "Three Dots" Separator --}}
        @if (is_string($element))
        	<li class="paginate_button disabled" aria-disabled="true"><span>{{ $element }}</span></li>
        @endif
        
        {{-- Array Of Links --}}
        @if (is_array($element))
            @foreach ($element as $page => $url)
                @if ($page == $paginator->currentPage())
                    <li class="paginate_button active" aria-current="page"><span>{{ $page }}</span></li>
                @else
                    <li class="paginate_button"><a href="{{ $url }}">{{ $page }}</a></li>
                @endif
            @endforeach
        @endif
        @endforeach
        
        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
        	<li class="paginate_button"> <a href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="@lang('pagination.next')">&rsaquo;</a> </li>
        @else
        	<li class="paginate_button disabled" aria-disabled="true" aria-label="@lang('pagination.next')"> <span aria-hidden="true">&rsaquo;</span> </li>
        @endif
      </ul>
    </div>
  </div>
</div>
@endif 
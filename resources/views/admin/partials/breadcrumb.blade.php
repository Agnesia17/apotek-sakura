<!-- [ breadcrumb ] start -->
<div class="page-header">
  <div class="page-block">
    <div class="row align-items-center">
      <div class="col-md-12">
        <div class="page-header-title">
          <h5 class="m-b-10">@yield('page-title', 'Dashboard')</h5>
        </div>
        <ul class="breadcrumb">
          {{-- <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li> --}}
           <li class="breadcrumb-item"><a href="#">Home</a></li>
          @if(isset($breadcrumbs))
            @foreach($breadcrumbs as $breadcrumb)
              @if($loop->last)
                <li class="breadcrumb-item" aria-current="page">{{ $breadcrumb['title'] }}</li>
              @else
                <li class="breadcrumb-item">
                  @if(isset($breadcrumb['url']))
                    <a href="{{ $breadcrumb['url'] }}">{{ $breadcrumb['title'] }}</a>
                  @else
                    <a href="javascript: void(0)">{{ $breadcrumb['title'] }}</a>
                  @endif
                </li>
              @endif
            @endforeach
          @else
            <li class="breadcrumb-item"><a href="javascript: void(0)">Dashboard</a></li>
            <li class="breadcrumb-item" aria-current="page">@yield('page-title', 'Home')</li>
          @endif
        </ul>
      </div>
    </div>
  </div>
</div>
<!-- [ breadcrumb ] end -->
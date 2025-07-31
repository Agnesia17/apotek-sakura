<!DOCTYPE html>
<html lang="en">
@include('admin.partials.head')

<body data-pc-preset="preset-1" data-pc-direction="ltr" data-pc-theme="light">
  @include('admin.partials.preloader')
  
  @include('admin.partials.sidebar')
  
  @include('admin.partials.header')

  <!-- [ Main Content ] start -->
  <div class="pc-container">
    <div class="pc-content">
      {{-- @include('admin.partials.breadcrumb') --}}
      
      <!-- [ Main Content ] start -->
      <div class="row">
        @yield('content')
      </div>
    </div>
  </div>
  <!-- [ Main Content ] end -->
  
  @include('admin.partials.footer')

  @include('admin.partials.scripts')
</body>
</html>
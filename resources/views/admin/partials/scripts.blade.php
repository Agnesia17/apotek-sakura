<!-- Required Js -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="{{ asset('assets/admin/js/plugins/popper.min.js') }}"></script>
<script src="{{ asset('assets/admin/js/plugins/simplebar.min.js') }}"></script>
<script src="{{ asset('assets/admin/js/plugins/bootstrap.min.js') }}"></script>
<script src="{{ asset('assets/admin/js/fonts/custom-font.js') }}"></script>
<script src="{{ asset('assets/admin/js/pcoded.js') }}"></script>
<script src="{{ asset('assets/admin/js/plugins/feather.min.js') }}"></script>

@stack('page-scripts')

<script>layout_change('light');</script>
<script>change_box_container('false');</script>
<script>layout_rtl_change('false');</script>
<script>preset_change("preset-1");</script>
<script>font_change("Public-Sans");</script>

@stack('scripts')
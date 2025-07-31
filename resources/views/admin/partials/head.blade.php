<!-- [Head] start -->
<head>
  <title>@yield('title', 'Dashboard') | Apotek Sakura</title>
  <!-- [Meta] -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="description" content="Mantis is made using Bootstrap 5 design framework. Download the free admin template & use it for your project.">
  <meta name="keywords" content="Mantis, Dashboard UI Kit, Bootstrap 5, Admin Template, Admin Dashboard, CRM, CMS, Bootstrap Admin Template">
  <meta name="author" content="CodedThemes">
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <!-- [Favicon] icon -->
  <link rel="icon" href="{{ asset('assets/admin/images/sakurapotek2.ico') }}" type="image/x-icon">
  
  <!-- [Google Font] Family -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@300;400;500;600;700&display=swap" id="main-font-link">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/themify-icons@1.0.1/css/themify-icons.css">

  
  <!-- [Tabler Icons] https://tablericons.com -->
  <link rel="stylesheet" href="{{ asset('assets/admin/fonts/tabler-icons.min.css') }}" >
  
  <!-- [Feather Icons] https://feathericons.com -->
  <link rel="stylesheet" href="{{ asset('assets/admin/fonts/feather.css') }}" >
  
  <!-- [Font Awesome Icons] https://fontawesome.com/icons -->
  <link rel="stylesheet" href="{{ asset('assets/admin/fonts/fontawesome.css') }}" >
  
  <!-- [Material Icons] https://fonts.google.com/icons -->
  <link rel="stylesheet" href="{{ asset('assets/admin/fonts/material.css') }}" >
  
  <!-- [Template CSS Files] -->
  <link rel="stylesheet" href="{{ asset('assets/admin/css/style.css') }}" id="main-style-link" >
  <link rel="stylesheet" href="{{ asset('assets/admin/css/style-preset.css') }}" >

  @stack('styles')
</head>
<!-- [Head] end -->
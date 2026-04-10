<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="robots" content="noindex, follow" />

    <meta name="keywords" content="Sherehe, Sherehe Digital, Harusi, Send Off, Party">
    <meta name="author" content="Jackson Mwatuka">
    <meta name="application-name" content="Sherehe Digital">

    @hasSection('desc')
        <meta name="description" content="@yield('desc')">
    @else
        <meta name="description" content="The Best digital cards for your event. Digitalise event planing and invitations">
    @endif


{{--    <meta name="description" content="@hasSection('template_title')@yield('template_title') @endif">--}}
    <meta property="og:title" content="@hasSection('template_title')@yield('template_title') | @endif {{ config('app.name', Lang::get('titles.app')) }}">
    <meta property="og:url" content="{{Request::url()}}">
    <meta property="og:description" content="@hasSection('class_desc')@yield('class_desc') @endif" />
    @yield('meta')

    <!-- Startup configuration -->

    <link rel="manifest" href="/chat-manifest.json" />
    <link href="{{url('/')}}/assets/plugins/global/plugins.bundle15aa.css?v=7.2.2" rel="stylesheet" type="text/css" />

    <!-- Google fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com/">
    <link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Work+Sans:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&amp;display=swap" rel="stylesheet">

    <link href="https://fonts.googleapis.com/css2?family=Material+Icons+Outlined" rel="stylesheet">
{{--  <link rel="stylesheet" href="../../../cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" integrity="sha512-1ycn6IcaQQ40/MKBW2W4Rhis/DbILU74C1vSrLJxCq57o941Ym01SwNsOMqvEBFlcgUa6xLiPY/NS5R+E6ztJQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />--}}
{{--  <link rel="stylesheet" href="../../../unpkg.com/leaflet%401.7.1/dist/leaflet.css" integrity="sha512-xodZBNTC5n17Xt2atTPuE1HxjVMSvLVW9ocqUKLsCC5CXdbqCmblAshOMAS6/keqq/sMZMZ19scR4PsZChSR7A==" crossorigin="" />--}}

    <link rel="shortcut icon" href="/logo.png">
    <!-- Stylesheets -->
    <link rel="stylesheet" href="/css/vendors.css">
    <link rel="stylesheet" href="/css/main.css">

    <style>
        .lds-ellipsis {
          display: inline-block;
          position: relative;
          width: 80px;
          height: 80px;
        }
        .lds-ellipsis div {
          position: absolute;
          top: 33px;
          width: 13px;
          height: 13px;
          border-radius: 50%;
          background: darkblue;
          animation-timing-function: cubic-bezier(0, 1, 1, 0);
        }
        .lds-ellipsis div:nth-child(1) {
          left: 8px;
          animation: lds-ellipsis1 0.6s infinite;
        }
        .lds-ellipsis div:nth-child(2) {
          left: 8px;
          animation: lds-ellipsis2 0.6s infinite;
        }
        .lds-ellipsis div:nth-child(3) {
          left: 32px;
          animation: lds-ellipsis2 0.6s infinite;
        }
        .lds-ellipsis div:nth-child(4) {
          left: 56px;
          animation: lds-ellipsis3 0.6s infinite;
        }
        @keyframes lds-ellipsis1 {
          0% {
            transform: scale(0);
          }
          100% {
            transform: scale(1);
          }
        }
        @keyframes lds-ellipsis3 {
          0% {
            transform: scale(1);
          }
          100% {
            transform: scale(0);
          }
        }
        @keyframes lds-ellipsis2 {
          0% {
            transform: translate(0, 0);
          }
          100% {
            transform: translate(24px, 0);
          }
        }
    </style>


    @livewireStyles

  <title>@hasSection('template_title')@yield('template_title') | @endif {{ config('app.name', Lang::get('titles.app')) }}</title>
</head>

<body class="preloader-visible" data-barba="wrapper">

  <!-- preloader start -->
  <div class="preloader js-preloader">
    <div class="preloader__bg"></div>
  </div>
  <!-- preloader end -->

  <!-- barba container start -->
  <div class="barba-container" data-barba="container">
    <main class="main-content  ">
        @include('sherehe.includes.header')

        @yield("content")

        @include('sherehe.includes.footer')
    </main>
  </div>

{{--  <script type="text/javascript">--}}
{{--      var LHCChatboxOptions = {hashchatbox:'empty',identifier:'default',status_text:'Chatbox Two'};--}}
{{--      (function() {--}}
{{--          var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;--}}
{{--          po.src = 'http://livehelperchat_laravel.test/index.php/chatbox/getstatus/(position)/bottom_right/(top)/300/(units)/pixels/(width)/300/(height)/300/(chat_height)/220';--}}
{{--          var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);--}}
{{--      })();--}}
{{--  </script>--}}

  <!-- barba container end -->

  <!-- JavaScript -->
{{--  <script src="../../../unpkg.com/leaflet%401.7.1/dist/leaflet.js" --}}
{{--          integrity="sha512-XQoYMqMTK8LvdxXYG3nZ448hOEQiglfqkJs1NOQV44cWnUrBc8PkAOcXy20w0vlaXaVUearIOBhiXZ5V3ynxwA==" crossorigin=""></script>--}}

  @livewireScripts

  <script src="/js/vendors.js"></script>
  <script src="/js/main.js"></script>

  @yield('page_js')

<!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-CP9F4QW1JC"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'G-CP9F4QW1JC');
</script>

<script>
    var stat = null;
    var dd = "No";

    @if(session()->has('status'))
      stat = "{{session()->get('status')}}";
      dd = "Something Went Wrong!";
    @endif

    @if(session()->has('message'))
     dd = "{{session()->get('message')}}";
    @endif
</script>


  <script src="{{url('/')}}/assets/plugins/global/plugins.bundle15aa.js?v=7.2.2"></script>

</body>

</html>

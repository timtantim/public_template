<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="api-token" content="{{ Auth::user()->api_token }}">
    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">

    <!-- Styles -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}"></script>
</head>

<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-100">
        @include('layouts.navigation')

        <!-- Page Heading -->
        {{-- <header class="bg-white shadow">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header> --}}
        <div class="d-flex container-fluid p-0 app-main">

            <nav id="sidebar" style="height: 100vh;">
                <div class="custom-menu">
                    <button type="button" id="sidebarCollapse" class="btn btn-primary">
                        <i class="fa fa-bars"></i>
                        <span class="sr-only">Toggle Menu</span>
                    </button>
                </div>
                <div class="p-4">
                    <h1><a href="index.html" class="logo nounderline">公版<span>Template</span></a></h1>
                    <ul class="list-unstyled components mb-5">

                        @permission('1')

                            <li class="active">
                                <a class="nounderline" href="{{ url(app()->getLocale() . '/human_resource_info') }}"
                                    style="font-size: 20px;"><span
                                        class="fa fa-home mr-3"></span>@lang('menu.human_resource')</a>
                            </li>
                            @endpermission

                            <li class="active">
                                <a class="nounderline"
                                    href="{{ url(app()->getLocale() . '/details_table_practicce') }}"
                                    style="font-size: 20px;"><span
                                        class="fa fa-user mr-3"></span>@lang('menu.order_manage')</a>
                            </li>
                            <li class="active">
                                <a class="nounderline" href="{{ url(app()->getLocale() . '/vue_practice') }}"
                                    style="font-size: 20px;"><span
                                        class="fa fa-user mr-3"></span>@lang('menu.vue_practice')</a>
                            </li>
                            {{-- <li class="active">
                                <a class="nounderline" href="{{ url('/template_create_time_table') }}"
                                    style="font-size: 20px;"><span class="fa fa-briefcase mr-3"></span>建立工作班</a>
                            </li>
                            <li class="active">
                                <a class="nounderline" href="#" style="font-size: 20px;"><span
                                        class="fa fa-sticky-note mr-3"></span>工作班休假預排</a>
                            </li>
                            <li class="active">
                                <a class="nounderline" href="#" style="font-size: 20px;"><span
                                        class="fa fa-suitcase mr-3"></span> 工作班排程</a>
                            </li> --}}
                        </ul>

                        <div class="mb-5">
                            <h3 class="h6 mb-3">Subscribe for newsletter</h3>
                            <form action="#" class="subscribe-form">
                                <div class="form-group d-flex">
                                    <div class="icon"><span class="icon-paper-plane"></span></div>
                                    <input type="text" class="form-control" placeholder="Enter Email Address">
                                </div>
                            </form>
                        </div>

                        <div class="footer">
                            <p>
                                <!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->
                                <script>
                                    document.write(new Date().getFullYear());
                                </script> All rights reserved | This template is made with <i class="icon-heart"
                                    aria-hidden="true"></i> by <a href="https://colorlib.com"
                                    target="_blank">Colorlib.com</a>
                                <!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->
                            </p>
                        </div>

                    </div>
                </nav>
                {{-- <div class="d-flex flex-column content">
                    {{ $content ?? '' }}
                  </div> --}}
                <!-- Page Content -->

                <main style="width: 100%;">
                    {{-- {{ $slot }} --}}
                    {{-- <div id="content" style="padding-left: 0px; padding-right:0px;padding-top:0px;padding-bottom:0px;"> --}}

                   

                        {{ $content ?? '' }}
                        {{-- {{ $content }} --}}

               
            </div>

            </main>
        </div>
        </div>
        {{-- <script src="{{ asset('js/app.js') }}" ></script> --}}
        {{-- <script src="{{ mix('js/app.js') }}"></script> --}}

      
      
      

        <script>
            $(document).ready(function() {
                "use strict";

                var fullHeight = function() {

                    $('.js-fullheight').css('height', $(window).height());
                    $(window).resize(function() {
                        $('.js-fullheight').css('height', $(window).height());
                    });

                };
                fullHeight();

                $('#sidebarCollapse').on('click', function() {

                    $('#sidebar').toggleClass('active');
                });
            });
        </script>
    </body>

    </html>

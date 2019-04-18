@include('public.top')
<body>
    <div class="maincont">
        @yield('content');
    </div>
    @include('public.footer')
    <!--焦点轮换-->
    @if(Route::has('index'))
    <script src="/js/jquery.excoloSlider.js"></script>
    <script>
        $(function () {
            $("#sliderA").excoloSlider();
        });
    </script>
    @endif
</body>

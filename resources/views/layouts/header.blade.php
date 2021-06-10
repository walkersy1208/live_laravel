@section('header')
    <div class="row">
        @if(Auth::check())
            <header-component :notify_data="{{ Auth::user()->Notifications }}"
                :is_login=true
                request_url=""
                :username="'{{Auth::user()->name}}'"
            >
            </header-component>
        @else
            <header-component request_url={{url()->current()}} is_login={{ Auth::check() ? true : false }}></header-component>
        @endif
    </div>
@endsection
@section('header')
   <div class="row">
        @if(JWTAuth::parseToken()->authenticate())
        <header-component 
                :gray_color="true" 
                :is_login=true
                request_url=""
                logout_url ="/api/logout"
                type="admin"
                >
            </header-component>
        @else
            <header-component :gray_color="true" request_url={{url()->current()}} is_login={{ Auth::check() ? true : false }}></header-component>
        @endif
    </div>
@endsection
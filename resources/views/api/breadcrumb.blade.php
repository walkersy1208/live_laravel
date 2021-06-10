<div>
    <nav aria-label="breadcrumb" style="margin-top:10px;">
        <ol class="breadcrumb">
            @foreach($data as $name=>$url)
                <li class="breadcrumb-item">
                    <a href="{{$url}}" style="font-size:16px;color:#333!important;">{{$name}}</a>
                </li>
            @endforeach
        </ol>
    </nav>
</div>

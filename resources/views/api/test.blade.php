@extends('layouts.app')
@section('content')
   <div><img data-src="https://ss1.bdstatic.com/70cFvXSh_Q1YnxGkpoWK1HF6hhy/it/u=4133203590,3794322263&fm=26&gp=0.jpg" alt="" style="width:100%"></div>
   <div><img data-src="https://ss1.bdstatic.com/70cFvXSh_Q1YnxGkpoWK1HF6hhy/it/u=4133203590,3794322263&fm=26&gp=0.jpg" alt="" style="width:100%"></div>
   <div><img data-src="https://ss1.bdstatic.com/70cFvXSh_Q1YnxGkpoWK1HF6hhy/it/u=4133203590,3794322263&fm=26&gp=0.jpg" alt="" style="width:100%"></div>
   <div><img data-src="https://ss1.bdstatic.com/70cFvXSh_Q1YnxGkpoWK1HF6hhy/it/u=4133203590,3794322263&fm=26&gp=0.jpg" alt="" style="width:100%"></div>
   <div><img data-src="https://ss1.bdstatic.com/70cFvXSh_Q1YnxGkpoWK1HF6hhy/it/u=4133203590,3794322263&fm=26&gp=0.jpg" alt="" style="width:100%"></div>
   <div><img data-src="https://ss1.bdstatic.com/70cFvXSh_Q1YnxGkpoWK1HF6hhy/it/u=4133203590,3794322263&fm=26&gp=0.jpg" alt="" style="width:100%"></div> 
@section('js')


function lazyLoad(imgs) { 
    var H = document.documentElement.clientHeight;//获取可视区域高度
    var S = document.documentElement.scrollTop || document.body.scrollTop;
    var imgs = document.querySelectorAll('img');
    
    for (var i = 0; i < imgs.length; i++) {
        if((H+S) > imgs[i].offsetTop){
           imgs[i].src = imgs[i].getAttribute('data-src');
           //console.log(imgs[i].offsetTop);
        }else{
            imgs[i].src = "";
        }
       
    }
}
window.onload=window.onscroll = function () { //onscroll()在滚动条滚动的时候触发
    lazyLoad();
}
@endsection
@endsection

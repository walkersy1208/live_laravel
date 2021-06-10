@extends('layouts.app')
@section('carousel')
<!-- @if(Auth::user())
    <spaheader-component 
        ref="headerCom"
        :notify_data="{{ Auth::user()->Notifications }}"
        :is_login=true
    >
    </spaheader-component>
@else
  <spaheader-component 
    ref="headerCom"
  >
  </spaheader-component>
@endif -->

@endsection
<style>

.view-leave-active {
    transition: opacity 0.2s ease-in-out, transform 0.2s ease;
}

.view-enter-active {
    transition: opacity 0.2s ease-in-out, transform 0.2s ease;
    transition-delay: 0.2s;
}

.view-enter, .view-leave-to {
    opacity: 0;
}

.view-enter-to, .view-leave {
    opacity: 1;
}
/*::cue.fade-enter-active, .fade-leave-active {
  transition: opacity .9s;
}
.fade-enter, .fade-leave-to  .fade-leave-active below version 2.1.8 {
  opacity: 0;
} */
</style>
<!--
        <transition name="fade">
              <router-view :key="$route.fullPath"></router-view>
        </transition>

-->
@section('content')
<transition name="view">
    <router-view :key="$route.fullPath"></router-view>
</transition>
<div style="padding-bottom:40px;"></div>
@section('js')
    
@endsection
@endsection

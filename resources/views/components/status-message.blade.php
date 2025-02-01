@props(['class' => ''])
@if(session('status.error'))
    <div class="alert alert-danger {{$class}}" {{$attributes}}>{{session('status.error')}}</div>
@elseif(session('status.info'))
    <div class="alert alert-primary {{$class}}" {{$attributes}}>{{session('status.info')}}</div>
@elseif(session('status.success'))
    <div class="alert alert-success {{$class}}" {{$attributes}}>{{session('status.success')}}</div>
@elseif(session('status'))
    <div class="alert alert-primary {{$class}}" {{$attributes}}>{{session('status')}}</div>
@endif

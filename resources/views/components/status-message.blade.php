@props([
	'class' => '',
	'scope' => 'status'
])

@if(session("$scope.error"))
    <div class="alert alert-danger {{$class}}" {{$attributes}}>{{session("$scope.error")}}</div>
@elseif(session("$scope.info"))
    <div class="alert alert-primary {{$class}}" {{$attributes}}>{{session("$scope.info")}}</div>
@elseif(session("$scope.success"))
    <div class="alert alert-success {{$class}}" {{$attributes}}>{{session("$scope.success")}}</div>
@elseif(session($scope))
    <div class="alert alert-primary {{$class}}" {{$attributes}}>{{session($scope)}}</div>
@endif

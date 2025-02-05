@props([
	'status' => null,
	'class' => '',
])

@php($statusColors = [
    'pending'    => 'text-bg-warning',
    'waiting'    => 'text-bg-purple',
    'processing' => 'text-body bg-info-subtle',
    'completed'  => 'text-bg-success',
    'cancelled'  => 'text-bg-secondary',
])

<span class="badge bg-gradient {{$statusColors[$status->value] ?? ''}} {{$class}}">
    {{$status->getShortName()}}
</span>

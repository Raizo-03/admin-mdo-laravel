@extends('layouts.app')

@section('title', 'Doctors')

@section('content')
<div class="space-y-5">
    <div class="border-b border-gray-700 pb-3">
        <h1 class="text-2xl font-bold text-white">Doctors Management</h1>
 
    </div>
@livewire('doctors-table')


</div>
@endsection

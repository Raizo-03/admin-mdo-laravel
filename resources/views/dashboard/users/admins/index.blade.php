@extends('layouts.app')

@section('title', 'Admins')

@section('content')
<div class="space-y-5">
    <div class="border-b border-gray-700 pb-3">
        <h1 class="text-2xl font-bold text-white">Admin Management</h1>
 
    </div>
@livewire('admins-table')


</div>
@endsection

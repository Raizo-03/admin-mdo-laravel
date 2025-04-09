@extends('layouts.app')

@section('title', 'Appointments')

@section('content')
<div class="space-y-5">
    <div class="border-b border-gray-700 pb-3">
        <h1 class="text-2xl font-bold text-white">Appointment Management</h1>
 
    </div>
    @livewire('appointment-confirmed-table')

</div>
@endsection

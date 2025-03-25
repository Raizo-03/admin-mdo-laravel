@extends('layouts.app')

@section('title', 'Feedbacks')

@section('content')
<div class="space-y-5">
    <div class="border-b border-gray-700 pb-3">
        <h1 class="text-2xl font-bold text-white">Content Management</h1>
    </div>

@livewire('feedback-table')


</div>
@endsection
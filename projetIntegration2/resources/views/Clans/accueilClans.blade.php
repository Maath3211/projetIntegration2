@extends('Layouts.app')
@section('style')
    <link type="stylesheet" href="{{ asset('css/Clans.css') }}">
@endsection()

@section('contenu')

<aside class="w-16 bg-gray-900 text-white h-screen flex flex-col items-center py-4 space-y-4">
       <a> <div class="w-10 h-10 bg-gray-600 rounded-full"></div>
       <a> <div class="w-10 h-10 bg-gray-500 rounded-full"></div>
       <a> <div class="w-10 h-10 bg-red-600 rounded-full"></div>
       <a> <div class="w-10 h-10 bg-yellow-500 rounded-full"></div>
       <a><div class="w-10 h-10 bg-blue-700 rounded-full"></div>
       <a><div class="w-10 h-10 bg-white border border-gray-700 rounded-full"></div>
       <a> <div class="w-10 h-10 bg-blue-900 rounded-full"></div>
    </aside> 
</header>

@endsection()
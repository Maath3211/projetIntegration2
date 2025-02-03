@extends('Layouts.app')
@section('style')
    <link type="stylesheet" href="{{ asset('css/Clans.css') }}">
@endsection()

@section('contenu')

<div class="contenuPrincipal flex-1 p-4">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-2">
                <div class="p-4">
                    <h3>Column 1</h3>
                    <p>Content for column 1 goes here.</p>
                </div>
            </div>
            <div class="col-md-7">
                <div class="p-4">
                    <h3>Column 2</h3>
                    <p>Content for column 2 goes here.</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="p-4">
                    <h3>Column 3</h3>
                    <p>Content for column 3 goes here.</p>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection()
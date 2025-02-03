@extends('Layouts.app')
@section('style')
    <link type="stylesheet" href="{{ asset('css/Clans.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&display=swap" rel="stylesheet">
    <style>
        .conteneurImage{
            background-image: url('{{ asset('img/workoutMasterLogo.jpg') }}');
            background-size: cover;
            background-position: center center;
            height: 150px;
        }
    </style>
@endsection()

@section('contenu')

<div class="contenuPrincipal flex-1">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-2 colonneChannels">
                <div class="container">
                    <div class="column">
                        <div class="conteneurImage">
                            <div class="texteSurImage">Workout Master</div>
                        </div>
                        <div>

                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-7">
                <div>
                    <h3>Column 2</h3>
                    <p>Content for column 2 goes here.</p>
                </div>
            </div>
            <div class="col-md-3">
                <div>
                    <h3>Column 3</h3>
                    <p>Content for column 3 goes here.</p>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection()
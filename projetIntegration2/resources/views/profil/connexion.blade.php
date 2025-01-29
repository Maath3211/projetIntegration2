@extends('layouts.app')
@section('contenu')
    @if (session('message'))
        <div class="alert alert-success">
            <p class="text-center msgErreur">{{ session('message') }}</p>
        </div>
    @endif
<div class="text-center">
    <h1 class="portail">Portail des fournisseurs</h1>
</div>
<form action="" method="GET">
  @csrf
  <div class="d-flex row justify-content-center text-center">
    <div class="form-group">
        <button type="submit" class="btn btn-secondary inscription">
            <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" fill="currentColor" class="bi bi-pencil-square me-2" viewBox="0 0 16 16">
                <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z"/>
            </svg>
            Pas inscrit ?
        </button>
    </div>
  </div>
</form>
    <div class="col-md-6 col-index">
      <form method="post" action="">
        @csrf
        <fieldset class="field-index">
          <legend>Authentification par adresse courriel</legend>
          <div class="d-flex flex-column justify-content-center py-5">
            <div class="form-group">
              <label for="email" class="titreForm">Adresse courriel</label>
              <input type="email" class="form-control control-index" id="email" placeholder="Adresse courriel" name="email">
              <a href="" class="link-right">NEQ?</a>
              @error('email')
              <span class="text-danger">{{ $message }}
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-x-octagon-fill" viewBox="0 0 16 16">
                  <path d="M11.46.146A.5.5 0 0 0 11.107 0H4.893a.5.5 0 0 0-.353.146L.146 4.54A.5.5 0 0 0 0 4.893v6.214a.5.5 0 0 0 .146.353l4.394 4.394a.5.5 0 0 0 .353.146h6.214a.5.5 0 0 0 .353-.146l4.394-4.394a.5.5 0 0 0 .146-.353V4.893a.5.5 0 0 0-.146-.353zm-6.106 4.5L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 1 1 .708-.708"/>
                </svg>
              </span>
              @enderror
            </div>
            <div class="form-group">
              <label for="password" class="titreForm">Mot de passe</label>
              <input type="password" class="form-control control-index" id="password" placeholder="Mot de passe" name="password">
              <a href="" class="link-right">Mot de passe oublié ?</a>
              @error('password')
              <span class="text-danger">{{ $message }}
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-x-octagon-fill" viewBox="0 0 16 16">
                  <path d="M11.46.146A.5.5 0 0 0 11.107 0H4.893a.5.5 0 0 0-.353.146L.146 4.54A.5.5 0 0 0 0 4.893v6.214a.5.5 0 0 0 .146.353l4.394 4.394a.5.5 0 0 0 .353.146h6.214a.5.5 0 0 0 .353-.146l4.394-4.394a.5.5 0 0 0 .146-.353V4.893a.5.5 0 0 0-.146-.353zm-6.106 4.5L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 1 1 .708-.708"/>
                </svg>
              </span>
              @enderror
            </div>
            <div class="form-group">
              <button type="submit" class="btn btn-secondary">Connexion</button>
            </div>
          </div>
        </fieldset>
      </form>
    </div>
  </div>
</div>
@endsection
@extends('layouts.apps')

@section('title', 'Atenção')
@section('function_name', 'null')

@section('content')
    <div class="uk-card uk-card-default uk-card-body">
        <article class="uk-article">
            <h1 class="uk-article-title" style="text-transform: uppercase; font-size: 1em;"><span style="font-size: 1.5em; margin-right: 0.2em;" uk-icon="warning"></span> Atenção <strong>{{$user->name}}</strong></h1>
            <div class="uk-alert-danger" uk-alert>
                <p>Você não tem permissões suficientes para acessar esta página.</p>
            </div>
        </article>
    </div>
@endsection

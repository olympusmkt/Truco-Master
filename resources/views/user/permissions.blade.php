@extends('layouts.app')

@section('title', 'Atenção')

@section('content')
<div class="uk-container" style="margin-top: 3em; margin-bottom: 3em;">
    <div class="uk-card uk-card-default uk-card-body">
        <article class="uk-article">
            <h1 class="uk-article-title" style="text-transform: uppercase; font-size: 1em;"><span style="font-size: 1.5em; margin-right: 0.2em;" uk-icon="warning"></span> Atenção <strong>{{$user->name}}</strong></h1>
            <div class="uk-alert-danger" uk-alert>
                <p>Seu plano expirou, renove o mesmo para ter acesso a essa página.</p>
            </div>
        </article>
    </div>
</div>
@endsection

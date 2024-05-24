@extends('layouts.apps')

@section('title', 'Listar Usuários')
@section('function_name', 'user_manager')

@section('content')
    <div>
        <div class="uk-grid" uk-grid style="align-items: center;">
            <div class="uk-width-1-2@s">
                <div class="line-section-list">
                    <h1 style="margin-top: 0em; margin-bottom: 0em;" class="title-dash-admin-draken">Listagem de Usuários</h1>
                    <p style="margin-top: 0em; margin-bottom: 0em;">Gostaria de editar ou ver os detalhes de algum usuário?</p>
                </div>
            </div>
            <div class="uk-width-1-2@s item-page">
                {{ $select_users->links() }}
            </div>
        </div>
        <hr>
        <div class="uk-margin" style="width: 100%;">
            <form class="uk-search uk-search-default" style="width: 100%;" method="POST" action="">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <button type="submit" name="search" class="uk-search-icon-flip" uk-search-icon></button>
                <input class="uk-search-input" type="search" placeholder="Digite um e-mail ou nome" name="value" value="{{ $value_search; }}">
            </form>
        </div>
        
        <?php if ($count_users > 0) { ?>
        <table id="example" class="uk-table uk-table-hover uk-table-striped" style="width:100%">
            <thead>
            <tr>
                <th><span style="margin-right: 0.3em;" uk-icon="mail"></span> E-mail</th>
                <th><span style="margin-right: 0.3em;" uk-icon="user"></span> Nome</th>
                <th><span style="margin-right: 0.3em;" uk-icon="file-edit"></span> Editar</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($select_users as $post)
                <tr>
                    <td><a style="color: #666; font-weight: 500;"><img class="uk-border-circle" style="width: 2.3em; margin-right: 0.5em;" src="{{ asset('assets/icons/users/149071.png') }}">{{ $post->email }}</a></td>
                    <td><a style="color: #666; font-weight: 500;">{{ $post->name }}</a></td>
                    <td><a href="usuario/{{ $post->id }}"><span class="uk-badge" style="text-transform: uppercase;">Editar Usuário</span></a></td>
                </tr>
            @endforeach
            </tbody>
        </table>
        <?php } else { ?>
            <?php if ($value_search != "") { ?>
                <div class="uk-alert-danger" uk-alert>
                    <p>Nenhum resultado encontrado, tente novamente com outro nome ou e-mail..</p>
                </div>
            <?php } else { ?>
                <div class="uk-alert-danger" uk-alert>
                    <p>Nenhum usuário foi encontrado em nosso banco de dados..</p>
                </div>
            <?php } ?>
        <?php } ?>
    </div>
@endsection

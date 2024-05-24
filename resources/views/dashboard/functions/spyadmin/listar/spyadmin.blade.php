@extends("layouts.apps")
@section("title", "Listar spyadmins")
@section("function_name", "spyadmin_manager")

@section("content")
    <div class="dash-crow">
        <div class="uk-grid" uk-grid style="align-items: center;">
            <div class="uk-width-1-2@s">
                <div class="line-section-list">
                    <h1 style="margin-top: 0em; margin-bottom: 0em;" class="title-dash-admin-draken">Listagem de Investigações</h1>
                    <p style="margin-top: 0em; margin-bottom: 0em;">Gostaria de editar ou ver os detalhes de algum item?</p>
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
                <input class="uk-search-input" type="search" placeholder="Digite um título" name="value" value="{{ $value_search; }}">
            </form>
        </div>

        <?php if ($count_users > 0) { ?>
        <table id="example" class="uk-table uk-table-hover uk-table-striped" style="width:100%">
            <thead>
            <tr>
                <th><span style="margin-right: 0.3em;" uk-icon="user"></span> Título</th>
                <th><span style="margin-right: 0.3em;" uk-icon="calendar"></span> Criado</th>
                <th><span style="margin-right: 0.3em;" uk-icon="calendar"></span> Ultima Atualização</th>
                <th>Status</th>
                <th><span style="margin-right: 0.3em;" uk-icon="file-edit"></span> Editar</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($select_users as $post)
                <?php 
                $criado = date('d/m/Y h:i:s', strtotime($post->created_at));
                $editado = date('d/m/Y h:i:s', strtotime($post->updated_at));
                ?>
                <tr>
                    <td>{{ $post->title }}</td>
                    <td>{{ $criado }}</td>
                    <td>{{ $editado }}</td>
                    <td>
                        <?php if ($post->status == "1") { ?>
                            <span class="uk-badge" style="background-color: #1da07c;">EM ABERTO</span>
                        <?php } else { ?>
                            <span class="uk-badge" style="background-color: {{ env('APP_COLOR_PUBLIC') }}!important;">FECHADO</span>
                        <?php } ?>
                    </td>
                    <td><a href="{{ env('APP_URL') }}dashboard/investigacao/{{ $post->id }}"><span class="uk-badge" style="text-transform: uppercase;">Detalhes da Investigação</span></a></td>
                </tr>
            @endforeach
            </tbody>
        </table>
        <?php } else { ?>
            <?php if ($value_search != "") { ?>
                <div class="uk-alert-danger" uk-alert>
                    <p>Nenhum resultado encontrado, tente novamente com outro título..</p>
                </div>
            <?php } else { ?>
                <div class="uk-alert-danger" uk-alert>
                    <p>Nenhum item foi encontrado em nosso banco de dados..</p>
                </div>
            <?php } ?>
        <?php } ?>
    </div>
@endsection

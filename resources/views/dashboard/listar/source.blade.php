@extends("layouts.apps")
@section("title", "Listar Funções")
@section("function_name", "source_manager")

@section("content")
    <div>
        <div class="uk-grid" uk-grid style="align-items: center;">
            <div class="uk-width-1-2@s">
                <div class="line-section-list">
                    <h1 style="margin-top: 0em; margin-bottom: 0em;" class="title-dash-admin-draken">Listagem de Funções</h1>
                    <p style="margin-top: 0em; margin-bottom: 0em;">Gostaria de editar ou ver os detalhes de algum item?</p>
                </div>
            </div>
            <div class="uk-width-1-2@s item-page">
                {{ $select_functions->links() }}
            </div>
        </div>
        <hr>
        <div class="uk-margin" style="width: 100%;">
            <form class="uk-search uk-search-default" style="width: 100%;" method="POST" action="">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <button type="submit" name="search" class="uk-search-icon-flip" uk-search-icon></button>
                <input class="uk-search-input" type="search" placeholder="Digite o nome da função" name="value" value="{{ $value_search; }}">
            </form>
        </div>
        <?php if ($count_function > 0) { ?>
        <table id="example" class="uk-table uk-table-hover uk-table-striped" style="width:100%">
            <thead>
            <tr>
                <th><span style="margin-right: 0.3em;" uk-icon="bookmark"></span> Nome</th>
                <th><span style="margin-right: 0.3em;" uk-icon="cog"></span> Função</th>
                <th><span style="margin-right: 0.3em;" uk-icon="file-edit"></span> Editar</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($select_functions as $post)
                <tr>
                    <td>{{ $post->name }}</td>
                    <td>{{ $post->function }}_manager</td>
                    <td><a href="#modal-sections-{{ $post->id }}" uk-toggle><span class="uk-badge" style="text-transform: uppercase;">Detalhes da Função</span></a></td>
                </tr>
                <div id="modal-sections-{{ $post->id }}" uk-modal>
                    <div class="uk-modal-dialog">
                        <button class="uk-modal-close-default" type="button" uk-close></button>
                        <div class="uk-modal-header">
                            <h2 class="uk-modal-title">{{ $post->name }}</h2>
                        </div>
                        <div class="uk-modal-body">
                            <ul class="uk-list uk-list-striped">
                                <?php 
                                $json_item = json_decode($post->arrays);
                                foreach ($json_item->function as $key) {

                                    if ($key->navbar == "false") {
                                        $name_post_type = "POST";
                                    } else if ($key->navbar == "route") { 
                                        $name_post_type = "ROUTE";
                                    } else { 
                                        $name_post_type = "GET";
                                    }

                                    if ($key->navbar == "false") {
                                        $name_post_type_url = "";
                                    } else if ($key->navbar == "route") {  
                                        $name_post_type_url = "";
                                    } else {
                                        $name_post_type_url = ": ".$key->link."";
                                    }


                                    if ($key->navbar == "false") {
                                        $name_post_type_url_v = "".$key->controller_name."";
                                    } else if ($key->navbar == "route") {  
                                        $name_post_type_url_v = "routes/web.php";
                                    } else {
                                        $name_post_type_url_v = "".$key->controller_name."";
                                    }

                                    echo "<li>
                                    <span>Request: <strong>".$name_post_type." <span style='font-weight: normal; color: #333; font-size: 0.9em;'>".$name_post_type_url."</span></strong></span>
                                    <br>
                                    <span>Arquivo: <strong style='color: #87bf13;'>".$key->filler."</strong></span>
                                    <br>
                                    <span>Função: <strong style='color: #43b0d9;'>".$name_post_type_url_v."</strong></span>
                                    <br>
                                    <span>Caminho: <strong style='color: #dd8505;'>".$key->path."".$key->filler."</strong></span>
                                    </li>";
                                }
                                ?>
                            </ul>
                        </div>
                        <div class="uk-modal-footer uk-text-right">
                            <button class="uk-button uk-button-default uk-modal-close" type="button">Fechar</button>
                            <button class="uk-button uk-button-danger" type="button" onclick="item_delet_source('{{ $post->id }}', '{{ $post->type }}');">Remover</button>
                        </div>
                    </div>
                </div>
            @endforeach
            </tbody>
        </table>
        <script>
            function item_delet_source(id, type) {
                
                $.ajaxSetup({
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}
                });

                // values inputs
                var function_name = "{{ $post->function }}";
                var id_item = id;

                $.ajax({
                    dataType: 'json',
                    ContentType: 'application/json',
                    type: 'POST',
                    url: "{{ route('delet_Function_Dash.post') }}",
                    data: { id_item: id_item, function_name: function_name, type: type  },
                    success: function (data) {
                        if (data.errors == undefined) {
                            if (data.status == "true") {
                                // Interval redirect
                                UIkit.notification(data.log, 'success');
                                setTimeout(function () {
                                    location.reload();
                                }, 500);
                            } else {
                                UIkit.notification(data.log, 'danger');
                            }
                        } else {
                            $.each(data.errors, function (index, value) {
                                UIkit.notification(value, 'danger');
                            });
                        }
                    }
                });
            };
        </script>
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

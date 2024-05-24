@extends('layouts.apps')

@section('title', 'Source')
@section('function_name', 'source_manager')

@section('content')
    <div>
        <div>
            <h1 style="margin-top: 0em; margin-bottom: 0em;" class="title-dash-admin-draken">{{ env('APP_NAME_SIMPLE') }} SOURCE</h1>
            <p class="draken-margin-top"><strong>{{ env('APP_NAME_SIMPLE') }} Source</strong> é uma extensão simples para criar funções pré-configuradas  de forma rápida, assim economizando seu tempo de forma <strong>considerável.</strong></p>
        </div>
        <hr>
        <div class="uk-child-width-1-2@m uk-grid-small uk-grid-match" uk-grid>
            <div class="source-class-1">
                <div class="bg-item-source" style="background-image: url('{{ asset('assets/icons/draken/0ab8ce6d94dc4bd5183e953ad6ef797d.gif') }}');">
                    <div>
                        <h2 class="title-bg-info-source">{{ env('APP_NAME_SIMPLE') }} <strong>Source</strong></h2>
                    </div>
                </div>
            </div>
            <div class="source-class-2">
                <div class="uk-card uk-card-default uk-card-body">
                    <p class="draken-margin-top">Selecione as informações para continuar com a criação da função.</p>
                    <hr>
                    <div uk-filter="target: .js-filter">
                        <ul class="uk-subnav uk-subnav-pill">
                            <li class="uk-active" uk-filter-control="[data-color='white']"><a href="#">Opções Básicas</a></li>
                            <li uk-filter-control="[data-color='blue']"><a href="#">Inputs para Formulários</a></li>
                        </ul>

                        <ul class="js-filter uk-child-width-1-1 uk-child-width-1-1@m" uk-grid>
                            <li data-color="white">
                                <form class="uk-grid-small" uk-grid>
                                    <div class="uk-width-1-1@s">
                                        <hr>
                                    </div>
                                    <div class="uk-width-1-2@s">
                                        <label>Nome da função</label>
                                        <input class="uk-input" type="text" id="name" placeholder="Nome da função">
                                    </div>
                                    <div class="uk-width-1-2@s">
                                        <label>Slug da função</label>
                                        <input class="uk-input" type="text" id="function" placeholder="Slug da função">
                                    </div>
                                    <div class="uk-width-1-1@s">
                                        <label>Usuário ou Administrador?</label>
                                        <select class="uk-select" id="type_function">
                                            <option value="dashboard">Administrador -> Dashboard</option>
                                            <option value="user">Usuário -> Public User</option>
                                        </select>
                                    </div>
                                    
                                    <div class="uk-width-1-1@s" style="margin-top: 2em;">
                                        <div class="uk-card uk-card-default uk-card-body" style="padding: 1.5em 1em;">
                                            <label>Selecione as funções:</label>
                                            <hr>
                                            <div class="uk-margin uk-grid-small uk-child-width-auto uk-grid" style="margin-top: 0.2em!important;">
                                                <label><input class="uk-checkbox" id="add" type="checkbox"> Adicionar</label>
                                                <label><input class="uk-checkbox" id="list" type="checkbox"> Listar</label>
                                                <label><input class="uk-checkbox" id="edit" type="checkbox"> Editar</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="uk-width-1-1@s" style="margin-top: 2em;">
                                        <button id="btn-submit-function" type="button" class="uk-button uk-button-primary uk-button-primary-100-draken">Criar Função</button>
                                    </div>
                                </form>
                            </li>
                            <li data-color="blue">
                                <form class="uk-grid-small" uk-grid>
                                    <div class="uk-width-1-1@s">
                                        <hr>
                                        <link href="https://cdnjs.cloudflare.com/ajax/libs/jsoneditor/9.0.4/jsoneditor.css" rel="stylesheet" type="text/css">
                                        <script src="https://cdnjs.cloudflare.com/ajax/libs/jsoneditor/9.0.4/jsoneditor.min.js"></script>
                                        <div id="jsoneditor" style="width: 100%!important; height: 350px;"></div>
                                        <script>
                                            // create the editor
                                            const container = document.getElementById("jsoneditor")
                                            const options = {modes: ['code', 'form', 'text', 'tree', 'view', 'preview']}
                                            const editor = new JSONEditor(container, options)

                                            // set json
                                            const initialJson = {
                                                "inputs": [
                                                    {
                                                      "placeholder": "Título",
                                                      "type": "text",
                                                      "id": "title",
                                                      "validator": "required|min:3"
                                                    },
                                                    {
                                                      "placeholder": "Descrição",
                                                      "type": "textarea",
                                                      "id": "description",
                                                      "validator": "required|min:3"
                                                    }
                                                ]
                                            }
                                            editor.set(initialJson);
                                        </script>
                                    </div>
                                </form>
                            </li>
                        </ul>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php
    // $test = '{"inputs":[{"name":"Nome","placeholder":"text","id":"name"},{"name":"Sobrenome","placeholder":"text","id":"name"},{"name":"Nome","placeholder":"text","id":"name"}]}';
    // $json_inputs = json_decode($test, TRUE);
    // foreach ($json_inputs['inputs'] as $key => $value) {
    //     echo $value['name'];
    // }
    ?>
    <script type="text/javascript">
        $.ajaxSetup({
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
        });

        $("#btn-submit-function").click(function(e){
            // preventDefault
            e.preventDefault();

            // values inputs
            var name = $("#name").val();
            var function_name = $("#function").val();
            var type_function = $("#type_function option:selected").val();

            let checkbox_add = document.getElementById('add');
            if(checkbox_add.checked) {
                var checkbox_add_value = "true";
            } else {
                var checkbox_add_value = "false";
            }

            let checkbox_edit = document.getElementById('edit');
            if(checkbox_edit.checked) {
                var checkbox_edit_value = "true";
            } else {
                var checkbox_edit_value = "false";
            }

            let checkbox_list = document.getElementById('list');
            if(checkbox_list.checked) {
                var checkbox_list_value = "true";
            } else {
                var checkbox_list_value = "false";
            }

            // Get json inputs
            var Json_Item = JSON.stringify(editor.get());

            $.ajax({
                dataType: 'json',
                ContentType: 'application/json',
                type: 'POST',
                url: "{{ route('add_Function_Dash.post') }}",
                data: {
                    name: name,
                    function_name: function_name,
                    checkbox_add_value: checkbox_add_value,
                    Json_Item: Json_Item,
                    checkbox_edit_value: checkbox_edit_value,
                    checkbox_list_value: checkbox_list_value,
                    type_function: type_function
                },
                success: function (data) {
                    if(data.errors == undefined) {
                        if(data.status == "true") {
                            UIkit.notification(data.log, 'success');
                        } else {
                            UIkit.notification(data.log, 'danger');
                        }
                    } else {
                        $.each(data.errors, function(index, value) {
                            UIkit.notification(value, 'danger');
                        });
                    }
                }
            });
        });
    </script>
@endsection

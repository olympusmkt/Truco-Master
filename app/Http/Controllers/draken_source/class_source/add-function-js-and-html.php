<?php 

// Fopen checkbox_add_value content -> add functions HTML
$conteudo_dashboard = '@extends("'.$prefix_page.'")
@section("title", "Adicionar '.$request->name.'")
@section("function_name", "'.$request->function_name.'_manager")

@section("content")
    <div class="'.$prefix_class.'">
        <div>
            <h1 style="margin-top: 0em; margin-bottom: 0em;" class="title-dash-admin-draken">Adicionar '.$request->name.'</h1>
            <p style="margin-top: 0em;">Gostaria de adicionar algum item?</p>
        </div>
        <hr>
        <div>
            <div>
                <div class="uk-card uk-card-default uk-card-body">
                    <form class="uk-grid-small" uk-grid>
                        '.$form_html.'
                        <div class="uk-margin">
                            <button type="submit" id="btn-submit-'.$rand.'" class="uk-button uk-button-primary uk-width-1-1">Adicionar '.$request->name.'</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <script type="text/javascript">
        $.ajaxSetup({
            headers: { "X-CSRF-TOKEN": "{{ csrf_token() }}" }
        });

        $("#btn-submit-'.$rand.'").click(function(e){
            // preventDefault
            e.preventDefault();

            // values inputs
            '.$form_ajax_1.'
            $.ajax({
                dataType: "json",
                ContentType: "application/json",
                type: "POST",
                url: "<?php echo env("APP_URL"); ?>add_'.$request->function_name.'",
                data: {
                    '.$formated_inserts_6.'
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
';



$conteudo_controller .= "
    // Add function post 
    public function add_".$request->function_name."(Request **request) {
        //Validation inputs
        **validator = Validator::make(**request->all(),
            [
                $formated_inserts_4
            ], [
                'required' => 'O campo :attribute é obrigatório',
                'min' => 'O campo :attribute deve ter pelo menos 6 caracteres.',
                'unique' => 'O campo :attribute já está em uso.',
                'confirmed' => 'A confirmação do campo :attribute não confere.',
                'email' => 'O campo de e-mail deve ser um endereço de e-mail válido.'
            ], [
                $formated_inserts_5
            ]
        );

        // Validation erros
        if (**validator->fails()){
            return response()->json([
                'status' => false,
                'errors' => **validator->errors()
            ]);
        } else {
            // Keys values static
            **created_at = date('Y-m-d H:i:s');

            // Insert into user new
            **request_user = DB::insert('insert into ".$request->function_name." (".$formated_inserts.", created_at) values (".$formated_inserts_2.", ?)',
                [
                   $formated_inserts_3, **created_at
                ]
            );

            // Return 
            **id = DB::getPdo()->lastInsertId();
            return response()->json(['status' => 'true', 'log' => 'Item adicionado com sucesso.']);
        }

    }

    ";


$conteudo_controller .= "
    // Add preview function 
    public function preview_".$request->function_name."() {
        // Auth validation
        if (!auth()->check()) {
            // User is not authenticated, redirect to the login page
            return redirect(''.env('APP_URL').'".$prefix_table_name."/login');
        } else {

            // Get user id
            **id = Auth::user()->id;
            **user = User::find(**id);

            // Validation simple roles
            **roles = json_decode(**user->roules);
            if (**roles->roles->type == '".$prefix_table."') {
                // Return page view
                return view('".$request->type_function."/functions/".$request->function_name."/adicionar/".$request->function_name."', compact('user', 'roles'));
            } else {
                return redirect(''.env('APP_URL').'".$prefix_table_redirect."');
            }
        }
    }

    ";       

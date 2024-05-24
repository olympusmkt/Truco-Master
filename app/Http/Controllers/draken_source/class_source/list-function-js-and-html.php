<?php 

// Fopen checkbox_add_value content -> add functions HTML
$conteudo_dashboard = '@extends("'.$prefix_page.'")
@section("title", "Listar '.$request->name.'s")
@section("function_name", "'.$request->function_name.'_manager")

@section("content")
    <div class="'.$prefix_class.'">
        <div class="uk-grid" uk-grid style="align-items: center;">
            <div class="uk-width-1-2@s">
                <div class="line-section-list">
                    <h1 style="margin-top: 0em; margin-bottom: 0em;" class="title-dash-admin-draken">Listagem de '.$request->function_name.'s</h1>
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
                <th><span style="margin-right: 0.3em;" uk-icon="file-edit"></span> Editar</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($select_users as $post)
                <tr>
                    <td>{{ $post->title }}</td>
                    <td><a href="'.env('APP_URL').''.$prefix_table_name.'/'.$request->function_name.'/{{ $post->id }}/"><span class="uk-badge" style="text-transform: uppercase;">Editar Item</span></a></td>
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
';


$conteudo_controller .= "
    // List function
    public function ".$request->function_name."_list() {
        // Auth validation 
        if (!auth()->check()) {
            // User is not authenticated, redirect to the login page
            return redirect(''.env('APP_URL').'".$prefix_table_name."/login');
        } else {

            // Create session search
            if (isset(**_POST['search'])) {
                session(['search_".$request->function_name."' => **_POST['value']]);
            }

            // Validation search item
            if (Session::has('search_".$request->function_name."')) {
                **value_search = session('search_".$request->function_name."');
            } else {
                **value_search = '';
            }

            // If query select DB TABLE
            if (**value_search == '') {
                // Select users im DB
                **select_users = DB::table('".$request->function_name."')->paginate(20);
            } else {
                **select_users = DB::table('".$request->function_name."')->Where('title', 'like', '%'.**value_search.'%')->paginate(20);
            }

            // Count users page
            **count_users = **select_users->count();

            // Get user id
            **id = Auth::user()->id;
            **user = User::find(**id);

            // Validation simple roles
            **roles = json_decode(**user->roules);

            // Validation simple roles
            **roles = json_decode(**user->roules);
            if (**roles->roles->type == '".$prefix_table."') {
                // Return page view
                return view('".$request->type_function."/functions/".$request->function_name."/listar/".$request->function_name."', compact('select_users', 'value_search', 'count_users', 'roles'));
            } else {
                return redirect(''.env('APP_URL').'".$prefix_table_redirect."');
            }
        }
    }
    ";       

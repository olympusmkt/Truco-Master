<?php
namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Auth;
use File;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Session;


class DrakenController extends Controller {

    // Dashboard add function 
    public function add_Function_Dash(Request $request) {

        //Validation inputs
        $validator = Validator::make($request->all(),
            [
                'name' => 'required',
                'function_name' => 'required'
            ], [
                'required' => 'O campo :attribute é obrigatório'
            ], [
                'name'     => 'Nome',
                'function_name'     => 'Nome da Função'
            ]
        );

        // Validation erros
        if ($validator->fails()){
            return response()->json([
                "status" => false,
                "errors" => $validator->errors()
            ]);
        } else {

            //config itens public user e admin
            if ($request->type_function == "dashboard") {
                $prefix_table = "customize_admin";
                $prefix_table_redirect = "dashboard/minha-conta";
                $prefix_table_name = "dashboard";
                $prefix_page = "layouts.apps";
                $prefix_class = "dash-crow";
            } else {
                $prefix_table = "simple_user";
                $prefix_table_redirect = "user/minha-conta";
                $prefix_table_name = "user";
                $prefix_page = "layouts.app";
                $prefix_class = "uk-container top-draken-source-item-public";
            }

            // Seletct - encontro a function igual
            $select_function_item = DB::table("functions")->where('name', '=',''.$request->name.'')->where('function', '=',''.$request->function_name.'')->limit('1')->get();

            // Count Seletct
            $select_function_item_total = $select_function_item->count();

            if ($select_function_item_total == 0) {

                // Criando pastas para arquivos
                mkdir(''.base_path().'/resources/views/'.$request->type_function.'/functions/'.$request->function_name.'/', 0777, true);
                mkdir(''.base_path().'/resources/views/'.$request->type_function.'/functions/'.$request->function_name.'/adicionar/', 0777, true);
                mkdir(''.base_path().'/resources/views/'.$request->type_function.'/functions/'.$request->function_name.'/listar/', 0777, true);
                mkdir(''.base_path().'/resources/views/'.$request->type_function.'/functions/'.$request->function_name.'/editar/', 0777, true);
            }

            // Inputs json itens
            $json_inputs = json_decode($request->Json_Item, TRUE);
            $rand = rand(100000, 1000000);
            $count_functions = 0;
            $conteudo_controller = "";
            $routes_return = "";
            $array_Menu = "";
            $routes_return_fixed_true = "<?php
            use App\-Http\-Controllers\-".$request->function_name."Controller;
            ";
            $routes_return_fixed_full = str_replace('-', '', $routes_return_fixed_true);
            $routes_return_fixed = $routes_return_fixed_full;

            // Validation option item add functions
            if ($request->checkbox_add_value == "true") {

                // Variables html and ajax
                $form_html = "";
                $form_ajax_1 = "";
                $form_ajax_2 = "";
                $form_php_1 = "";
                $form_php_2 = "";
                $form_php_3 = "";
                $form_php_4 = "";
                $form_php_5 = "";


                // Html form generate
                require ''.base_path() .'/app/Http/Controllers/draken_source/class_source/add-foreach-form.php'; 

                // Formated inserts
                $formated_inserts = substr($form_php_1, 0, -2);
                $formated_inserts_2 = substr($form_php_2, 0, -2);
                $formated_inserts_3 = substr($form_php_3, 0, -2);
                $formated_inserts_4 = substr($form_php_4, 0, -2);
                $formated_inserts_5 = substr($form_php_5, 0, -2);
                $formated_inserts_6 = substr($form_ajax_2, 0, -2);


                //Require htmls and js - ADD FUNCTION
                require ''.base_path() .'/app/Http/Controllers/draken_source/class_source/add-function-js-and-html.php';   


                // Formated itens
                $conteudo_controller_replace = str_replace('**', '$', $conteudo_controller);


                // Create path filler
                $arquivo_dashboard = fopen(''.base_path() .'/resources/views/'.$request->type_function.'/functions/'.$request->function_name.'/adicionar/'.$request->function_name.'.blade.php', 'w');

                // Create checkbox_add_value content
                fwrite($arquivo_dashboard, $conteudo_dashboard);
                fclose($arquivo_dashboard);

                // Routes and count functions
                $count_functions++;
                $routes_return .= "
                Route::get('/".$request->type_function."/adicionar-$request->function_name', [".$request->function_name."Controller::class, 'preview_".$request->function_name."']);
                <br>
                Route::post('add_".$request->function_name."', [".$request->function_name."Controller::class, 'add_".$request->function_name."'])->name('add_".$request->function_name.".post');
                ";

                $routes_return_fixed .= "
                // Add function controller 
                Route::get('/".$request->type_function."/adicionar-$request->function_name', [".$request->function_name."Controller::class, 'preview_".$request->function_name."']);
                Route::post('add_".$request->function_name."', [".$request->function_name."Controller::class, 'add_".$request->function_name."'])->name('add_".$request->function_name.".post');
                ";

                $array_Menu .= '{
                  "name": "Adicionar '.$request->name.'",
                  "link": "/'.$request->type_function.'/adicionar-'.$request->function_name.'",
                  "function_name": "'.$request->function_name.'",
                  "path": "'.$request->type_function.'/functions/'.$request->function_name.'/adicionar/",
                  "navbar": "true",
                  "filler": "'.$request->function_name.'.blade.php",
                  "controller_name": "preview_'.$request->function_name.'"
                }, 

                {
                  "name": "POST '.$request->name.'",
                  "link": "add_'.$request->function_name.'.post",
                  "function_name": "'.$request->function_name.'",
                  "path": "app/Http/Controllers/",
                  "navbar": "false",
                  "filler": "'.$request->function_name.'Controller.php",
                  "controller_name": "add_'.$request->function_name.'"
                }, ';
            }

            // Validation option item edit functions
            if ($request->checkbox_edit_value == "true") {

                // Variables html and ajax
                $form_html = "";
                $form_ajax_1 = "";
                $form_ajax_2 = "";
                $form_php_1 = "";
                $form_php_2 = "";
                $form_php_3 = "";
                $form_php_4 = "";
                $form_php_5 = "";

                // Html form generate
                require ''.base_path() .'/app/Http/Controllers/draken_source/class_source/edit-foreach-form.php'; 

                // Formated inserts
                $formated_inserts = substr($form_php_1, 0, -2);
                $formated_inserts_2 = substr($form_php_2, 0, -2);
                $formated_inserts_3 = substr($form_php_3, 0, -2);
                $formated_inserts_4 = substr($form_php_4, 0, -2);
                $formated_inserts_5 = substr($form_php_5, 0, -2);
                $formated_inserts_6 = substr($form_ajax_2, 0, -2);


                //Require htmls and js - ADD FUNCTION
                require ''.base_path() .'/app/Http/Controllers/draken_source/class_source/edit-function-js-and-html.php';   


                // Formated itens
                $conteudo_controller_replace = str_replace('**', '$', $conteudo_controller);


                // Create path filler
                $arquivo_dashboard = fopen(''.base_path() .'/resources/views/'.$request->type_function.'/functions/'.$request->function_name.'/editar/'.$request->function_name.'.blade.php', 'w');

                // Create checkbox_add_value content
                fwrite($arquivo_dashboard, $conteudo_dashboard);
                fclose($arquivo_dashboard);

                // Routes and count functions
                $count_functions++;
                $routes_return .= "
                <br>
                Route::get('/".$request->type_function."/$request->function_name/{id_item}', [".$request->function_name."Controller::class, 'preview_edit_".$request->function_name."']);
                <br>
                Route::post('edit_".$request->function_name."', [".$request->function_name."Controller::class, 'edit_".$request->function_name."'])->name('edit_".$request->function_name.".post');
                ";

                $routes_return_fixed .= "
                // Edit function controller 
                Route::get('/".$request->type_function."/$request->function_name/{id_item}', [".$request->function_name."Controller::class, 'preview_edit_".$request->function_name."']);
                Route::post('edit_".$request->function_name."', [".$request->function_name."Controller::class, 'edit_".$request->function_name."'])->name('edit_".$request->function_name.".post');
                ";

                $array_Menu .= '{
                  "name": "Editar '.$request->name.'",
                  "link": "/'.$request->type_function.'/'.$request->function_name.'/{id_item}",
                  "function_name": "'.$request->function_name.'",
                  "path": "'.$request->type_function.'/functions/'.$request->function_name.'/editar/",
                  "navbar": "true",
                  "filler": "'.$request->function_name.'.blade.php",
                  "controller_name": "preview_edit_'.$request->function_name.'"
                }, 

                {
                  "name": "POST '.$request->name.'",
                  "link": "edit_'.$request->function_name.'.post",
                  "function_name": "'.$request->function_name.'",
                  "path": "app/Http/Controllers/",
                  "navbar": "false",
                  "filler": "'.$request->function_name.'Controller.php",
                  "controller_name": "edit_'.$request->function_name.'"
                }, ';
            }

            // Validation option item edit functions
            if ($request->checkbox_list_value == "true") {

                // Variables html and ajax
                $form_html = "";
                $form_ajax_1 = "";
                $form_ajax_2 = "";
                $form_php_1 = "";
                $form_php_2 = "";
                $form_php_3 = "";
                $form_php_4 = "";
                $form_php_5 = "";

                // Html form generate
                require ''.base_path() .'/app/Http/Controllers/draken_source/class_source/list-foreach-form.php'; 

                // Formated inserts
                $formated_inserts = substr($form_php_1, 0, -2);
                $formated_inserts_2 = substr($form_php_2, 0, -2);
                $formated_inserts_3 = substr($form_php_3, 0, -2);
                $formated_inserts_4 = substr($form_php_4, 0, -2);
                $formated_inserts_5 = substr($form_php_5, 0, -2);
                $formated_inserts_6 = substr($form_ajax_2, 0, -2);


                //Require htmls and js - ADD FUNCTION
                require ''.base_path() .'/app/Http/Controllers/draken_source/class_source/list-function-js-and-html.php';   


                // Formated itens
                $conteudo_controller_replace = str_replace('**', '$', $conteudo_controller);


                // Create path filler
                $arquivo_dashboard = fopen(''.base_path() .'/resources/views/'.$request->type_function.'/functions/'.$request->function_name.'/listar/'.$request->function_name.'.blade.php', 'w');

                // Create checkbox_add_value content
                fwrite($arquivo_dashboard, $conteudo_dashboard);
                fclose($arquivo_dashboard);

                // Routes and count functions
                $count_functions++;
                $routes_return .= "
                <br>
                Route::match(['get', 'post'], '/".$request->type_function."/listar-".$request->function_name."s/', [".$request->function_name."Controller::class, '".$request->function_name."_list']);
                ";

                $routes_return_fixed .= "
                // List function controller 
                Route::match(['get', 'post'], '/".$request->type_function."/listar-".$request->function_name."s/', [".$request->function_name."Controller::class, '".$request->function_name."_list']);
                ";

                $array_Menu .= '{
                  "name": "Listar '.$request->name.'",
                  "link": "/'.$request->type_function.'/listar-'.$request->function_name.'s",
                  "function_name": "'.$request->function_name.'",
                  "path": "'.$request->type_function.'/functions/'.$request->function_name.'/listar/",
                  "navbar": "true",
                  "filler": "'.$request->function_name.'.blade.php",
                  "controller_name": "'.$request->function_name.'_list"
                }, ';
            }

            $array_Menu .= '{
              "name": "Route => '.$request->name.'",
              "link": "controllers/route_source/",
              "function_name": "'.$request->function_name.'",
              "path": "Controllers/route_source/",
              "navbar": "route",
              "filler": "'.$request->function_name.'_routes.php",
              "controller_name": "'.$request->function_name.'_routes.php"
            }, ';

            // Validation count functions 
            if ($count_functions > 0) {

                //Require PHP - ADD FUNCTION
                require ''.base_path() .'/app/Http/Controllers/draken_source/class_source/add-function-php-controller.php';   

                // Create path filler Controller
                $arquivo_controller = fopen(''.base_path() .'/app/Http/Controllers/'.$request->function_name.'Controller.php', 'w');

                // Create checkbox_add_value content
                fwrite($arquivo_controller, $controller_full);
                fclose($arquivo_controller);

                // Create path filler Controller
                $arquivo_controller_routes = fopen(''.base_path() .'/routes/groups/'.$request->function_name.'.php', 'w');

                // Create checkbox_add_value content
                fwrite($arquivo_controller_routes, ''.$routes_return_fixed.'');
                fclose($arquivo_controller_routes);

                // Formated arrays
                $array_Menu_Formated = substr($array_Menu, 0, -2);

                //valido se vai ser update ou insert
                if ($select_function_item_total > 0) {

                    foreach ($select_function_item as $key_total) {
                        // Updated_at date
                        $updated_at = date('Y-m-d H:i:s');
                        // Insert into user new
                        $request_updater_user = DB::update('update functions set arrays = ?, routes = ?, updated_at = ? where id = ?', [
                            '{ "function": [ '.$array_Menu_Formated.' ]}',
                            ''.$routes_return.'',
                            $updated_at,
                            $key_total->id
                        ]);
                    }

                } else {

                    // Insert into user new
                    $created_at = date('Y-m-d H:i:s');
                    $request_functions = DB::insert(
                        'insert into functions (name, function, arrays, routes, type, created_at) values (?, ?, ?, ?, ?, ?)',
                        [
                            $request->name,
                            $request->function_name,
                            '{ "function": [ '.$array_Menu_Formated.' ]}',
                            ''.$routes_return.'',
                            $prefix_table,
                            $created_at
                        ]
                    );

                }

                // Return 
                $return_inner = "include_once(__DIR__ . '/groups/".$request->function_name.".php');";
                return response()->json(['status' => 'true',  'log' => "$return_inner"]);

            } else {

                // Return error
                return response()->json(['status' => 'false',  'log' => "Nenhuma função foi selecionada, selecione alguma para continuar."]);
            }

        }
        
    }

    // Public page source function
    public function preview_Source(){

        if (!auth()->check()) {
            // User is not authenticated, redirect to the login page
            return redirect('dashboard/login');
        } else {

            // Get user id
            $id = Auth::user()->id;
            $user = User::find($id);

            // Validation simple roles
            $roles = json_decode($user->roules);
            if ($roles->roles->type == "customize_admin") {

                // Get functions acess roles
                $functions_for_user = $roles->roles->acess;
                $functions_for_user_arrays = explode(", ", $functions_for_user);

                // For validation values
                $source_manager = 0;
                foreach ($functions_for_user_arrays as $row_user) {
                    if ($row_user == "source_manager") {
                        $source_manager++;
                    }
                }

                // Validation source permission
                if ($source_manager > 0) {
                    // Return page view
                    return view('dashboard/adicionar/source', compact('user', 'roles'));
                } else {
                    return redirect('dashboard/permissoes');
                }

            } else {
                return redirect('../user/minha-conta');
            }
        }
    }

    // Public list sources function
    public function preview_Source_List(){

        if (!auth()->check()) {
            // User is not authenticated, redirect to the login page
            return redirect('dashboard/login');
        } else {

            // Get user id
            $id = Auth::user()->id;
            $user = User::find($id);

            // Validation simple roles
            $roles = json_decode($user->roules);
            if ($roles->roles->type == "customize_admin") {


                // Create session search
                if (isset($_POST['search'])) {
                    session(['source_teste' => $_POST['value']]);
                }

                // Validation search item
                if (Session::has('source_teste')) {
                    $value_search = session('source_teste');
                } else {
                    $value_search = '';
                }

                // If query select DB TABLE
                if ($value_search == '') {
                    // Select users im DB
                    $select_functions = DB::table('functions')->paginate(20);
                } else {
                    $select_functions = DB::table('functions')->Where('name', 'like', '%'.$value_search.'%')->paginate(20);
                }

                // Count users page
                $count_function = $select_functions->count();

                // Get functions acess roles
                $functions_for_user = $roles->roles->acess;
                $functions_for_user_arrays = explode(", ", $functions_for_user);

                // For validation values
                $source_manager = 0;
                foreach ($functions_for_user_arrays as $row_user) {
                    if ($row_user == "source_manager") {
                        $source_manager++;
                    }
                }

                // Validation source permission
                if ($source_manager > 0) {
                    // Return page view
                    return view('dashboard/listar/source', compact('select_functions', 'value_search', 'count_function', 'roles'));
                } else {
                    return redirect('dashboard/permissoes');
                }
                
            } else {
                return redirect('../user/minha-conta');
            }
        }
    }


    // Dashboard Delet function 
    public function delet_Function_Dash(Request $request) {

        //Validation inputs
        $validator = Validator::make($request->all(),
            [
                'id_item' => 'required',
                'function_name' => 'required'
            ], [
                'required' => 'O campo :attribute é obrigatório'
            ], [
                'id_item' => 'ID Item',
                'function_name' => 'Nome da Função'
            ]
        );

        // Validation erros
        if ($validator->fails()){
            return response()->json([
                "status" => false,
                "errors" => $validator->errors()
            ]);
        } else {

            // Validation path
            if ($request->type == "simple_user") {
                $type_path = "user";
            } else {
                $type_path = "dashboard";
            }

            // delet controllers
            $delet_controller = app_path().'/Http/Controllers/'.$request->function_name.'Controller.php';
            if (file_exists($delet_controller)) {
                unlink($delet_controller);
            }

            // delet add
            $delet_add = base_path().'/resources/views/'.$type_path.'/functions/'.$request->function_name.'/adicionar/'.$request->function_name.'.blade.php';
            if (file_exists($delet_add)) {
                unlink($delet_add);
            }

            // delet edit
            $delet_edit = base_path().'/resources/views/'.$type_path.'/functions/'.$request->function_name.'/editar/'.$request->function_name.'.blade.php';
            if (file_exists($delet_edit)) {
                unlink($delet_edit);
            }

            // delet list
            $delet_list = base_path().'/resources/views/'.$type_path.'/functions/'.$request->function_name.'/listar/'.$request->function_name.'.blade.php';
            if (file_exists($delet_list)) {
                unlink($delet_list);
            }

            // delet list
            $delet_routes = base_path().'/routes/groups/'.$request->function_name.'.php';
            if (file_exists($delet_routes)) {
                unlink($delet_routes);
            }

            //remover pastas internas
            rmdir(base_path().'/resources/views/'.$type_path.'/functions/'.$request->function_name.'/listar');
            rmdir(base_path().'/resources/views/'.$type_path.'/functions/'.$request->function_name.'/editar');
            rmdir(base_path().'/resources/views/'.$type_path.'/functions/'.$request->function_name.'/adicionar');
            rmdir(base_path().'/resources/views/'.$type_path.'/functions/'.$request->function_name.'/');

            // delet db item
            DB::table('functions')->where('id', $request->id_item)->delete();

            // Return error
            return response()->json(['status' => 'true',  'log' => "Itens e arquivos removidos com sucesso."]);

        } 
    }


}

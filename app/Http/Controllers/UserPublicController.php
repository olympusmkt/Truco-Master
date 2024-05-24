<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Auth\User;
use Auth;
use Session;

class UserPublicController extends Controller {

    // Public function register user post
    public function RegisterPublicUserPost(Request $request) {
        // Validator class item
        $validator = Validator::make($request->all(),
            [
                'name' => 'required',
                'email' => 'required|email|unique:users',
                'password' => 'required|min:6',
                'confirm_password' => 'required|min:6|same:password',
            ], [
                'required' => 'O campo :attribute é obrigatório',
                'min' => 'O campo :attribute deve ter pelo menos 6 caracteres.',
                'unique' => 'O campo :attribute já está em uso.',
                'email' => 'O campo de e-mail deve ser um endereço de e-mail válido.',
                'confirmed' => 'A confirmação do campo :attribute não confere.'
            ], [
                'name'      => 'Nome',
                'email'     => 'E-mail',
                'password'  => 'Senha',
                'confirm_password'  => 'Confirmar senha',
            ]
        );

        // Validation erros
        if ($validator->fails()){
            return response()->json([
                "status" => false,
                "errors" => $validator->errors()
            ]);
        } else {

            // Keys values static
            $created_at = date('Y-m-d H:i:s');
            $rand_key_row_1 = rand(10000, 1000000);
            $rand_key_row_2 = rand(50000, 9000000);

            // Insert into user new
            $request_user = DB::insert(
                'insert into users (name, email, password, created_at, token_user_login, roules, type) values (?, ?, ?, ?, ?, ?, ?, ?)',
                [
                    $request->name,
                    $request->email,
                    bcrypt($request->password),
                    $created_at,
                    bcrypt(''.$request->email.'-'.$rand_key_row_1.'-'.$rand_key_row_2.''),
                    '{"roles": { "type": "simple_user", "acess": "all" }}',
                    'simple_user'
                ]
            );

            // Auth validation redirects
            return response()->json(['status' => 'true', 'log' => 'Registro realizado com sucesso, realize seu login para continuar.', 'redirect' => 'login']);
        }
    }

    // Public function Login user post
    public function LoginPublicUserPost(Request $request) {

        // Validation inputs
        $validator = Validator::make($request->all(),
            [
                'email' => 'required|email',
                'password' => 'required|min:6',
            ], [
                'required' => 'O campo :attribute é obrigatório',
                'min' => 'O campo :attribute deve ter pelo menos 6 caracteres.',
                'unique' => 'O campo :attribute já está em uso.',
                'email' => 'O campo de e-mail deve ser um endereço de e-mail válido.',
            ], [
                'email'     => 'E-mail',
                'password'  => 'Senha',
            ]
        );

        // Validation erros
        if ($validator->fails()){
            return response()->json([
                "status" => false,
                "errors" => $validator->errors()
            ]);
        } else {

            // User data arrays
            $user_data = array(
                'email'  => $request->email,
                'password' => $request->password
            );

            // Auth validation user logged
            if(Auth::attempt($user_data))  {
                return response()->json(['status' => 'true', 'log' => 'Login realizado com sucesso.', 'redirect' => 'minha-conta']);
            } else {
                return response()->json(['status' => 'false', 'log' => 'Os dados digitados estão incorretos.']);
            }

        }

    }

    // Public function profile auth user
    public function userPublicProfile(){

        if (!auth()->check()) {
            // User is not authenticated, redirect to the login page
            return redirect('/user/login');
        } else {

            // Get user id
            $id = Auth::user()->id;
            $user = User::find($id);

            // Return page view
            return view('/user/minha-conta',compact('user'));
        }
    }

    // Public page register user
    public function userPublicRegisterPage(){
        if (!auth()->check()) {
            return view('/user/registro');
        } else {
            return redirect('/user/minha-conta');
        }
    }

    // Public page login user
    public function userPublicLoginPage(){

        if (!auth()->check()) {
            return view('/user/login');
        } else {
            return redirect('/user/minha-conta');
        }
    }

    // Function auth logout
    public function userPublicLogout(){
        Auth::logout();
        return redirect('/user/login');
    }


    // Function auth logout
    public function userCronExpira(){

        // If query select DB TABLE
        $select_users = DB::table("users")->where('type', '=','simple_user')->get();

        // Count users page
        $count_users = $select_users->count();

        if ($count_users > 0) {
            foreach ($select_users as $key_select_users) {
                if ($key_select_users->expirar == null) {
                    # code...
                } else {

                    // Date
                    $dt_atual = date("Y-m-d"); // data atual
                    $timestamp_dt_atual = strtotime($dt_atual); // converte para timestamp Unix

                    // Date
                    $dt_expira = $key_select_users->expirar; // data de expiração do anúncio
                    $timestamp_dt_expira = strtotime($dt_expira); // converte para timestamp Unix
                     
                    // data atual é maior que a data de expiração
                    if ($timestamp_dt_atual > $timestamp_dt_expira) {
                        echo "Seu anuncio expirou! Deseja renovar?";
                    } else { 
                        // false
                        echo "Anuncio ativo";
                    }
                }
            }
        }

    }

    // List users dashboard
    public function dashboardUsersList(){

        if (!auth()->check()) {
            return redirect('dashboard/login');
        } else {
            // Get user id
            $id = Auth::user()->id;

            // Get user details
            $user = User::find($id);

            // Create session search
            if (isset($_POST['search'])) {
                session(['search_user' => $_POST['value']]);
            }

            // Validation search item
            if (Session::has('search_user')) {
                $value_search = session('search_user');
            } else {
                $value_search = "";
            }

            // Validation e-mail type
            $validation_email = filter_var($value_search, FILTER_VALIDATE_EMAIL);

            // If query select DB TABLE
            if ($value_search == "") {
                // Select users im DB
                $select_users = DB::table("users")->where('type', '=','simple_user')->paginate(20);
            } else {
                if ($validation_email) {
                    // Select users im DB WHERE LIKE -> EMAIL
                    $select_users = DB::table("users")->Where('email', 'like', '%'.$value_search.'%')->where('type', '=','simple_user')->paginate(20);
                } else {
                    // Select users im DB WHERE LIKE -> NAME
                    $select_users = DB::table("users")->Where('name', 'like', '%'.$value_search.'%')->where('type', '=','simple_user')->paginate(20);
                }
            }

            // Count users page
            $count_users = $select_users->count();

            // Validation simple roles
            $roles = json_decode($user->roules);

            // If validation customize
            if ($roles->roles->type == "customize_admin") {

                // Get functions acess roles
                $functions_for_user = $roles->roles->acess;
                $functions_for_user_arrays = explode(", ", $functions_for_user);

                // For validation values
                $user_manager = 0;
                foreach ($functions_for_user_arrays as $row_user) {
                    if ($row_user == "user_manager") {
                        $user_manager++;
                    }
                }

                // Redirect Permission
                if ($user_manager > 0) {
                    // Return page view
                    return view('dashboard/listar/usuarios',compact('select_users', 'value_search', 'count_users', 'roles'));
                } else {
                    return redirect('dashboard/permissoes');
                }

            } else {
                return redirect('../user/minha-conta');
            }

        }
    }

    // Dashboard edit users
    public function dashboardUsersEdit($id_user){

        if (!auth()->check()) {
            return redirect('dashboard/login');
        } else {

            // Get user details
            $user_details = DB::table("users")->where('id', '=', $id_user)->get();

            // Get user id
            $id = Auth::user()->id;

            // Get user details
            $user = User::find($id);

            // Validation simple roles
            $roles = json_decode($user->roules);

            // Get user details
            $user_id_get = User::find($id_user);

            // Validation loged id
            if ($roles->roles->type == "customize_admin") {
                // Validation id get type
                if ($user_id_get->type == "simple_user") {

                    // Get functions acess roles
                    $functions_for_user = $roles->roles->acess;
                    $functions_for_user_arrays = explode(", ", $functions_for_user);

                    // For validation values
                    $user_manager = 0;
                    foreach ($functions_for_user_arrays as $row_user) {
                        if ($row_user == "user_manager") {
                            $user_manager++;
                        }
                    }

                    // Redirect Permission
                    if ($user_manager > 0) {
                        // Return page view
                        return view('dashboard/editar/usuario',compact('user_details', 'roles'));
                    } else {
                        return redirect('dashboard/permissoes');
                    }

                } else {
                    return redirect('dashboard/administrador/'.$id_user.'');
                }
            } else {
                return redirect('../user/minha-conta');
            }

        }
    }

    // Public page register user
    public function userPublicRegisterPageDashboard(){
        if (!auth()->check()) {
            return redirect('/dashboard/login');
        } else {

            // Get user id
            $id = Auth::user()->id;

            // Get user details
            $user = User::find($id);

            // Validation simple roles
            $roles = json_decode($user->roules);

            // Validation loged id
            if ($roles->roles->type == "customize_admin") {

                // Get functions acess roles
                $functions_for_user = $roles->roles->acess;
                $functions_for_user_arrays = explode(", ", $functions_for_user);

                // For validation values
                $user_manager = 0;
                foreach ($functions_for_user_arrays as $row_user) {
                    if ($row_user == "user_manager") {
                        $user_manager++;
                    }
                }

                // Redirect Permission
                if ($user_manager > 0) {
                    // Return page view
                    return view('/dashboard/adicionar/usuario', compact('roles'));
                } else {
                    return redirect('dashboard/permissoes');
                }

            } else {
                return redirect('../user/minha-conta');
            }

        }
    }

    // Function updater User
    public function updaterPublicUserPost(Request $request) {

        // Validator class item
        $validator = Validator::make($request->all(),
            [
                'name' => 'required',
                'phone' => 'required|min:10',
                'cpf' => 'required|min:10'
            ], [
                'required' => 'O campo :attribute é obrigatório',
                'min' => 'O campo :attribute deve ter pelo menos 6 caracteres.',
                'unique' => 'O campo :attribute já está em uso.',
                'confirmed' => 'A confirmação do campo :attribute não confere.'
            ], [
                'name' => 'Nome',
                'phone' => 'Telefone',
                'cpf' => 'CPF'
            ]
        );

        // Validation erros
        if ($validator->fails()){
            return response()->json([
                "status" => false,
                "errors" => $validator->errors()
            ]);
        } else {

            if (!auth()->check()) {
                return response()->json(['status' => 'false', 'log' => 'Aconteceu algum erro, tente novamente mais tarde.']);
            } else {

                // Get user id
                $auth = Auth::user();

                // Updated_at date
                $updated_at = date('Y-m-d H:i:s');

                // Insert into user new
                $request_updater_user = DB::update('update users set name = ?, updated_at = ?, phone = ?, cpf = ? where id = ?', [
                    $request->name,
                    $updated_at,
                    $request->phone,
                    $request->cpf,
                    $auth->id,
                ]);


                // validation redirects
                return response()->json(['status' => 'true', 'log' => 'Dados da conta atualizados com sucesso.']);
            }

        }
    }

    // Function updater User Pass Hash
    public function updaterPublicPassUserPost(Request $request) {

        // Validator class item
        $validator = Validator::make($request->all(),
            [
                'current_password' => 'required|string',
                'new_password' => 'required|confirmed|min:6|string'
            ], [
                'required' => 'O campo :attribute é obrigatório',
                'min' => 'O campo :attribute deve ter pelo menos 6 caracteres.',
                'confirmed' => 'A confirmação do campo :attribute não confere.'
            ], [
                'current_password'  => 'Senha atual',
                'new_password'      => 'Nova senha',
            ]
        );

        // Validation erros
        if ($validator->fails()){
            return response()->json([
                "status" => false,
                "errors" => $validator->errors()
            ]);
        } else {

            if (!auth()->check()) {
                return response()->json(['status' => 'false', 'log' => 'Aconteceu algum erro, tente novamente mais tarde.']);
            } else {

                // Auth request
                $auth = Auth::user();

                // The passwords matches
                if (!Hash::check($request->get('current_password'), $auth->password))
                {
                    return response()->json(['status' => 'false', 'log' => 'A senha atual é inválida.']);
                }

                // Current password and new password same
                if (strcmp($request->get('current_password'), $request->new_password) == 0)
                {
                    return response()->json(['status' => 'false', 'log' => 'A nova senha não pode ser igual à sua senha atual.']);
                }

                // Updated_at date
                $updated_at = date('Y-m-d H:i:s');
                $new_Pass = bcrypt($request->new_password);

                // Insert into user new
                DB::update('update users set password = ?, updated_at = ? where id = ?', [
                    $new_Pass,
                    $updated_at,
                    $auth->id
                ]);

                // validation redirects
                return response()->json(['status' => 'true', 'log' => 'Sua senha foi atualizada com sucesso.']);
            }

        }
    }

    // Function updater User
    public function updaterUserPostListPublic(Request $request) {

        function createSlug($string) {
            $table = array(','=>'', '.'=>'', '-'=>'');
            // -- Remove duplicated spaces
            $stripped = preg_replace(array('/\s{2,}/', '/[\t\n]/'), ' ', $string);
            // -- Returns the slug
            return strtolower(strtr($string, $table));
        }


        // Validator class item
        $validator = Validator::make($request->all(),
            [
                'name' => 'required'
            ], [
                'required' => 'O campo :attribute é obrigatório',
                'min' => 'O campo :attribute deve ter pelo menos 6 caracteres.',
                'unique' => 'O campo :attribute já está em uso.',
                'confirmed' => 'A confirmação do campo :attribute não confere.'
            ], [
                'name' => 'Nome'
            ]
        );

        // Validation erros
        if ($validator->fails()){
            return response()->json([
                "status" => false,
                "errors" => $validator->errors()
            ]);
        } else {

            if (!auth()->check()) {
                return response()->json(['status' => 'false', 'log' => 'Aconteceu algum erro, tente novamente mais tarde.']);
            } else {

                // Updated_at date
                $updated_at = date('Y-m-d H:i:s');

                // Validation user type roles json
                if ($request->user_type == "simple_user") {
                    $all_roules = '{"roles": { "type": "simple_user", "acess": "all" }}';
                } else {
                    $all_roules = '{"roles": { "type": "customize_admin", "acess": "'.env('APP_FUNCTIONS').'" }}';
                } 

                // Insert into user new
                $request_updater_user = DB::update('update users set name = ?, updated_at = ?, phone = ?, cpf = ?, type = ?, roules = ?, saldo = ?, afiliado = ?, demo = ?, bonus = ? where id = ?', [
                    $request->name,
                    $updated_at,
                    $request->phone,
                    $request->cpf,
                    $request->user_type,
                    $all_roules,
                    createSlug($request->saldo),
                    createSlug($request->afiliado),
                    createSlug($request->demo),
                    createSlug($request->bonus),
                    $request->id_user
                ]);

                if ($request->user_type == "simple_user") {
                    // validation redirects
                    return response()->json(['status' => 'true', 'log' => 'Dados do usuário atualizados com sucesso.']);
                } else {
                    // validation redirects
                    return response()->json(['status' => 'true', 'log' => 'Dados do usuário atualizados com sucesso.', 'redirect' => 'administrador/'.$request->id_user.'']);
                }

            }

        }
    }

    // Dashboard page register user
    public function RegisterUserPostDashboard(Request $request) {
        // Validator class item
        $validator = Validator::make($request->all(),
            [
                'name' => 'required',
                'email' => 'required|email|unique:users',
                'password' => 'required|min:6',
                'confirm_password' => 'required|min:6|same:password',
            ], [
                'required' => 'O campo :attribute é obrigatório',
                'min' => 'O campo :attribute deve ter pelo menos 6 caracteres.',
                'unique' => 'O campo :attribute já está em uso.',
                'email' => 'O campo de e-mail deve ser um endereço de e-mail válido.',
                'confirmed' => 'A confirmação do campo :attribute não confere.'
            ], [
                'name'      => 'Nome',
                'email'     => 'E-mail',
                'password'  => 'Senha',
                'confirm_password'  => 'Confirmar senha',
            ]
        );

        // Validation erros
        if ($validator->fails()){
            return response()->json([
                "status" => false,
                "errors" => $validator->errors()
            ]);
        } else {

            // Keys values static
            $created_at = date('Y-m-d H:i:s');
            $rand_key_row_1 = rand(10000, 1000000);
            $rand_key_row_2 = rand(50000, 9000000);

            // Insert into user new
            $request_user = DB::insert(
                'insert into users (name, email, password, created_at, token_user_login, roules, type) values (?, ?, ?, ?,?,?,?)',
                [
                    $request->name,
                    $request->email,
                    bcrypt($request->password),
                    $created_at,
                    bcrypt(''.$request->email.'-'.$rand_key_row_1.'-'.$rand_key_row_2.''),
                    '{"roles": { "type": "simple_user", "acess": {"dashboard": "false"} }}',
                    'simple_user'
                ]
            );
            $id = DB::getPdo()->lastInsertId();

            // Auth validation redirects
            return response()->json(['status' => 'true', 'log' => 'Usuário cadastro com sucesso.', 'redirect' => 'usuario/'.$id .'/']);
        }
    }


}

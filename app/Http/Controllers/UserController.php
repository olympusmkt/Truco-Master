<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Auth\User;
use Auth;
use Session;

class UserController extends Controller {
    
    // Public function Login user post
    public function LoginUserPost(Request $request) {

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
    public function userProfile(){

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
                // Return page view
                return view('dashboard/minha-conta', compact('user', 'roles'));
            } else {
                return redirect('../user/minha-conta');
            }
        }
    }

    // Public page login user
    public function userLoginPage(){

        if (!auth()->check()) {
            return view('dashboard/login');
        } else {
            return redirect('dashboard/minha-conta');
        }
    }

    // Public page index redirect
    public function index(){
        return "index";
    }

    // Function auth logout
    public function userLogout(){
        Auth::logout();
        return redirect('dashboard/login');
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
                session(['search_admin' => $_POST['value']]);
            }

            // Validation search item
            if (Session::has('search_admin')) {
                $value_search = session('search_admin');
            } else {
                $value_search = "";
            }

            // Validation e-mail type
            $validation_email = filter_var($value_search, FILTER_VALIDATE_EMAIL);

            // If query select DB TABLE
            if ($value_search == "") {
                // Select users im DB
                $select_users = DB::table("users")->where('type', '=','customize_admin')->paginate(20);
            } else {
                if ($validation_email) {
                    // Select users im DB WHERE LIKE -> EMAIL
                    $select_users = DB::table("users")->Where('email', 'like', '%'.$value_search.'%')->where('type', '=','customize_admin')->paginate(20);
                } else {
                    // Select users im DB WHERE LIKE -> NAME
                    $select_users = DB::table("users")->Where('name', 'like', '%'.$value_search.'%')->where('type', '=','customize_admin')->paginate(20);
                }
            }

            // Count users page
            $count_users = $select_users->count();

            // Validation simple roles
            $roles = json_decode($user->roules);

            // Validation customize user
            if ($roles->roles->type == "customize_admin") {

                // Get functions acess roles
                $functions_for_user = $roles->roles->acess;
                $functions_for_user_arrays = explode(", ", $functions_for_user);

                // For validation values
                $admin_manager = 0;
                foreach ($functions_for_user_arrays as $row_user) {
                    if ($row_user == "admin_manager") {
                        $admin_manager++;
                    }
                }

                if ($admin_manager > 0) {
                    // Return page view
                    return view('dashboard/listar/administradores',compact('select_users', 'value_search', 'count_users', 'roles'));
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
                if ($user_id_get->type == "customize_admin") {

                    // Get functions acess roles
                    $functions_for_user = $roles->roles->acess;
                    $functions_for_user_arrays = explode(", ", $functions_for_user);

                    // For validation values
                    $admin_manager = 0;
                    foreach ($functions_for_user_arrays as $row_user) {
                        if ($row_user == "admin_manager") {
                            $admin_manager++;
                        }
                    }

                    // Redirect Permission
                    if ($admin_manager > 0) {
                        // Return page view
                        return view('dashboard/editar/administrador',compact('user_details', 'roles'));
                    } else {
                        return redirect('dashboard/permissoes');
                    }

                } else {
                    return redirect('dashboard/usuario/'.$id_user.'');
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
                $admin_manager = 0;
                foreach ($functions_for_user_arrays as $row_user) {
                    if ($row_user == "admin_manager") {
                        $admin_manager++;
                    }
                }

                // Redirect Permission
                if ($admin_manager > 0) {
                    // Return page view
                    return view('/dashboard/adicionar/administrador', compact('roles'));
                } else {
                    return redirect('dashboard/permissoes');
                }

            } else {
                return redirect('../user/minha-conta');
            }
        }
    }

    // Function updater User
    public function updaterUserPost(Request $request) {

        // Validator class item
        $validator = Validator::make($request->all(),
            [
                'name' => 'required',
                'phone' => 'required|min:10',
                'cpf' => 'required|min:11'
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
    public function updaterPassUserPost(Request $request) {

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
    public function updaterUserPostList(Request $request) {

        // Validator class item
        $validator = Validator::make($request->all(),
            [
                'name' => 'required',
                'roules' => 'required|min:3',
                'user_type' => 'required|min:3',
            ], [
                'required' => 'O campo :attribute é obrigatório',
                'min' => 'O campo :attribute deve ter pelo menos 6 caracteres.',
                'unique' => 'O campo :attribute já está em uso.',
                'confirmed' => 'A confirmação do campo :attribute não confere.'
            ], [
                'name' => 'Nome',
                'roules' => 'Roles',
                'user_type' => 'Tipo de Usuário'
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
                    $all_roules = '{"roles": { "type": "customize_admin", "acess": "'.$request->roules.'" }}';
                }

                // Insert into user new
                $request_updater_user = DB::update('update users set name = ?, updated_at = ?, phone = ?, cpf = ?, type = ?, roules = ? where id = ?', [
                    $request->name,
                    $updated_at,
                    $request->phone,
                    $request->cpf,
                    $request->user_type,
                    $all_roules,
                    $request->id_user
                ]);

                if ($request->user_type == "customize_admin") {
                    // validation redirects
                    return response()->json(['status' => 'true', 'log' => 'Dados do administrador atualizados com sucesso.']);
                } else {
                    // validation redirects
                    return response()->json(['status' => 'true', 'log' => 'Dados do administrador atualizados com sucesso.', 'redirect' => 'usuario/'.$request->id_user.'']);
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
                    '{"roles": { "type": "customize_admin", "acess": "'.env('APP_FUNCTIONS').'" }}',
                    'customize_admin'
                ]
            );
            $id = DB::getPdo()->lastInsertId();

            // Auth validation redirects
            return response()->json(['status' => 'true', 'log' => 'Administrador cadastro com sucesso.', 'redirect' => 'administrador/'.$id .'/']);
        }
    }

}

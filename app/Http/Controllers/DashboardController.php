<?php
namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Auth;
use File;
// use Http;
use Illuminate\Support\Facades\Validator;

//testes
// use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;

class DashboardController extends Controller {


    // Public function dashboard home
    public function dashboardHome(){
        if (!auth()->check()) {
            return redirect('dashboard/login');
        } else {
            // Get user id
            $id = Auth::user()->id;

            // Get user details
            $user = User::find($id);

            // Date array item
            $month = array(
                1 => "01", 2 => "02", 3 => "03", 4 => "04", 5 => "05", 6 => "06",
                7 => "07", 8 => "08", 9 => "09", 10 => "10", 11 => "11", 12 => "12")
            ;

            // Arrays foreach date month -> admin
            $count_values_admin_array = "";
            foreach ($month as $month_arrays => $value) {
                $count_admin = DB::table("users")->where('type', '=','customize_admin')->whereMonth('created_at', '=', $value)->count();
                $count_values_admin_array .= "{$count_admin}, ";
            }

            // Arrays foreach date month -> user
            $count_values_user_array = "";
            foreach ($month as $month_arrays => $value) {
                $count_user = DB::table("users")->where('type', '=','simple_user')->whereMonth('created_at', '=', $value)->count();
                $count_values_user_array .= "{$count_user}, ";
            }

            // Validation simple roles
            $roles = json_decode($user->roules);
            if ($roles->roles->type == "customize_admin") {
                // Return page view
                return view('dashboard/welcome',compact('user', 'count_values_admin_array', 'count_values_user_array', 'roles'));
            } else {
                return redirect('../user/minha-conta');
            }

        }
    }

    // Public function dashboard home
    public function dashboardPermissions(){
        if (!auth()->check()) {
            return redirect('dashboard/login');
        } else {
            // Get user id
            $id = Auth::user()->id;

            // Get user details
            $user = User::find($id);

            // Validation simple roles
            $roles = json_decode($user->roules);
            if ($roles->roles->type == "customize_admin") {
                // Return page view
                return view('dashboard/permissions',compact('user', 'roles'));
            } else {
                return redirect('../user/minha-conta');
            }

        }
    }

    // Public function dashboard filler manager
    public function dashboardHomeManagerFiller(){
        if (!auth()->check()) {
            return redirect('dashboard/login');
        } else {
            // Get user id
            $id = Auth::user()->id;

            // Get user details
            $user = User::find($id);

            // Validation simple roles
            $roles = json_decode($user->roules);
            if ($roles->roles->type == "customize_admin") {

                // Return direct fillers
                $mediaPath = public_path('uploads/');
                $filesInFolder = File::allFiles($mediaPath);
                $allMedia = [];

                // Return for arrays
                foreach ($filesInFolder as $path) {
                    $files = pathinfo($path);
                    $allMedia[] = $files['basename'];
                }

                // Get functions acess roles
                $functions_for_user = $roles->roles->acess;
                $functions_for_user_arrays = explode(", ", $functions_for_user);

                // For validation values
                $filler_manager = 0;
                foreach ($functions_for_user_arrays as $row_user) {
                    if ($row_user == "filler_manager") {
                        $filler_manager++;
                    }
                }

                // Redirect Permission
                if ($filler_manager > 0) {
                    // Return page view
                    return view('dashboard/adicionar/gerenciador-de-arquivos',compact('user', 'allMedia', 'roles'));
                } else {
                    return redirect('dashboard/permissoes');
                }

            } else {
                return redirect('../user/minha-conta');
            }

        }
    }

    // Dashboard filler post
    public function dashboardHomeManagerFillerPost(Request $request) {

        // Validation inputs
        $validator = Validator::make($request->all(),
            [
                'filenames' => 'required|mimes:'.env('APP_UPLOADS_PERMISSIONS').''
            ], [
                'required' => 'O campo :attribute é obrigatório',
                'mimes' => 'Os :attribute devem ser do tipo: ('.env('APP_UPLOADS_PERMISSIONS').')'
            ], [
                'filenames'     => 'arquivos'
            ]
        );

        // Validation erros
        if ($validator->fails()){
            return response()->json([
                "status" => false,
                "errors" => $validator->errors()
            ]);
        }

        $images = $request->file('filenames');
        if ($images) {
            foreach ($images as $image) {
                $new_name = rand() . rand() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('uploads/'), $new_name);
            }
            return response()->json(['status' => 'true', 'log' => 'Upload de arquivos realizado com sucesso.', 'redirect' => 'gerenciador-de-arquivos']);
        } else {
            return response()->json(['status' => 'false', 'log' => 'Selecione alguns arquivos para continuar.', 'redirect' => 'gerenciador-de-arquivos']);
        }
    }

    // Public function dashboard filler manager
    public function Get_Tiger($path){
        $parts = explode('/', $path);
        $parts[0] = $parts[0] . '-2';
        $adjustedPath = implode("/", $parts);
        $localPath = public_path($adjustedPath);

        if (File::exists($localPath)) {
            dd("O arquivo já existe.");
        } else {
            // $response = Http::get("https://api.pgsoft-games.org/126/index.html?btt=1&ot=a0ded89ff26f7160b9692b251cf640a1&ops=lj5peWXjzSASa&l=pt&__refer=api.pgsoft-games.org&or=pgsoft-res.fineslotgame.com&__hv=1f8dac38");
            // $item_checked = $response->successful();

            // if ($item_checked == true) {
            //     $directoryPath = dirname($localPath);

            //     if (!File::exists($directoryPath)) {
            //         File::makeDirectory($directoryPath, mode: 0755, recursive: true, force: true);
            //     }

            //     File::put($localPath, $response->body());

            // }
        }
    }

    // Auth pg item tyger
    public function verifySession() {
        //$data = json_decode($this->rJson(), true)
        return response($this->rJson());
    }

    // public function verifyOperatorPlayerSession() {
    //     $data = json_decode($this->rJson(), true)
    //     return response()->json($data);
    // }

    public function rJson() {
        return '{"dt":{"oj":{"jid":1},"pid":"fcc703082ef12e77c110e04c9ec51cdb","pcd":"cha1_user1","tk":"fcc703082ef12e77c110e04c9ec51cdb","st":1,"geu":"game-api/fortune-tiger/","lau":"game-api/lobby/","bau":"web-api/game-proxy/","cc":"BRL","cs":"R$","nkn":"cha1_user1","gm":[{"gid":"126","msdt":1638432065000,"medt":1638432065000,"st":1,"amsg":""}],"uiogc":{"bb":1,"grtp":0,"gec":1,"cbu":0,"cl":0,"bf":0,"mr":0,"phtr":0,"vc":0,"bfbsi":0,"bfbli":0,"il":0,"rp":0,"gc":0,"ign":0,"tsn":0,"we":0,"gsc":0,"bu":0,"pwr":0,"hd":0,"et":0,"np":0,"igv":0,"as":0,"asc":0,"std":0,"hnp":0,"ts":0,"smpo":0,"grt":0,"ivs":1,"ir":0,"hn":1},"ec":[],"occ":{"rurl":"","tcm":"","tsc":0,"ttp":0,"tlb":"","trb":""},"ioph":"fcc703082ef12e77c110e04c9ec51cdb"},"err":null}';
    }
    

}

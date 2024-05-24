<?php 

// Controller base
$controller_full = "<?php
namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Auth;
use Session;


class ".$request->function_name."Controller extends Controller {
".$conteudo_controller_replace."
}

            ";
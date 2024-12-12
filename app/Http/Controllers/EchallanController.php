<?php
namespace App\Http\Controllers;

use App\Models\Echallan;
use Illuminate\Http\Request;

class EchallanController extends Controller
{
    public function index()
    {
        $echallans = Echallan::all();
        return view('echallan.index', compact('echallans'));
    }
}

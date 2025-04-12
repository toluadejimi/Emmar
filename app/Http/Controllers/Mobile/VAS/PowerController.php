<?php

namespace App\Http\Controllers\Mobile\VAS;

use App\Http\Controllers\Controller;
use App\Models\Power;
use App\Models\Setting;
use App\Services\BankOneService;
use App\Services\VTPass\VTPassService;
use Illuminate\Http\Request;

class PowerController extends Controller
{
    protected $VTpass;

    public function __construct(VTPassService $VTpass)
    {
        $this->VTpass = $VTpass;
    }


    public function power_biller()
   {
       $set = Setting::where('id', 1)->first();
       if ($set->bills_provider == "vt_pass") {

           $get_billers = Power::all()->makeHidden(['sn_code', 'name', 'vas_provider']);

           return response()->json([
               'status' => true,
               'data' => $get_billers
           ]);

       }



   }





}

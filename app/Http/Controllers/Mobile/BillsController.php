<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use App\Services\VTPassService;
use Illuminate\Http\Request;



class BillsController extends Controller
{

    protected $VTpass;

    public function __construct(VTPassService $VTpass)
    {
        $this->VTpass = $VTpass;
    }

    public function validate_meter(request $request)
    {

        $biller_code = $request->biller_code;
        $service_id = $request->service_id;

        $response = $this->VTpass->Validate($biller_code, $service_id);



   }

}

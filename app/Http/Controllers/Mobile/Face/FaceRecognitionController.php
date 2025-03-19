<?php

namespace App\Http\Controllers\Mobile\Face;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;

class FaceRecognitionController extends Controller
{
    public function compareFaces(Request $request)
    {

        $pythonPath = "/Applications/XAMPP/xamppfiles/htdocs/project/Emaar/myenv/bin/python3";
        $scriptPath = storage_path("app/python/compare_faces.py");

        $image1 = storage_path('app/public/faces/h8SwUsNWia2cb1dX1sdpLgi2okWwRlSbcgNPKJdD.png');
        $image2 = storage_path('app/public/' . $request->image2);

        if (file_exists($image2)) {
            return response()->json(["message" => "One or both images not found"], 400);
        }

        $command = escapeshellcmd("$pythonPath $scriptPath $image1 $image2 2>&1");
        $output = shell_exec($command);

        Log::error("Face Recognition Output: " . $output);

        return response()->json(["message" => trim($output) ?: "No output from script"]);


//        return response()->json([
//            "message" => trim($output) ?: "No output from script",
//        ]);
    }
}

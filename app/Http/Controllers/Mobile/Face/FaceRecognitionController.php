<?php

namespace App\Http\Controllers\Mobile\Face;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;

class FaceRecognitionController extends Controller
{
    public function compareFaces(Request $request)
    {

        $phone_no = preg_replace('/^0/', '', $request->phone);
        $phone = "+234" . $phone_no;

        $user_image = User::where('phone', $phone)->first()->image ?? null;
        if($user_image == null){
            return response()->json([
                'success' => false,
                'message' => ' User Image  not found'
            ], 404);
        }

        $pythonPath = "/var/www/html/Emaar/myenv/bin/python3";
        $scriptPath = storage_path("app/python/compare_faces.py");

        $image1 = storage_path('app/public/faces/'.$user_image);
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

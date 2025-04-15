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
        if ($user_image == null) {
            return response()->json([
                'success' => false,
                'message' => 'User image not found',
            ], 404);
        }

        // Paths
        $pythonPath = "/var/www/html/Emaar/myenv/bin/python3";
        $scriptPath = storage_path("app/python/compare_faces.py");
        $image1 = storage_path('app/public/' . $user_image);

        // Handle image2 upload (do not save permanently)
        if (!$request->hasFile('image2') || !$request->file('image2')->isValid()) {
            return response()->json(["message" => "Uploaded image2 is missing or invalid"], 400);
        }

        $tempPath = $request->file('image2')->storeAs('temp', uniqid() . '.' . $request->file('image2')->getClientOriginalExtension());

        $image2 = storage_path('app/' . $tempPath);

        // Run the Python script
        $command = escapeshellcmd("$pythonPath $scriptPath $image1 $image2 2>&1");
        $output = shell_exec($command);

        // Delete the temporary file
        if (file_exists($image2)) {
            unlink($image2);
        }

        Log::info("Running: $command");
        $output = shell_exec($command);
        Log::info("Face Recognition Output: " . $output);

        return response()->json(["message" => trim($output) ?: "No output from script"]);
    }

}

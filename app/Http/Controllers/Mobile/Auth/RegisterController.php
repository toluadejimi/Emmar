<?php

namespace App\Http\Controllers\Mobile\Auth;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Models\SmsCharge;
use App\Models\User;
use App\Services\BankOneService;
use App\Services\TermiiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{

    protected $bankOneService;

    public function __construct(BankOneService $bankOneService)
    {
        $this->bankOneService = $bankOneService;
    }

    public function step_1_registration(request $request)
    {

        $phone_no = preg_replace('/^\[?0\]?/', '', $request->phone);
        $phone = "+234" . $phone_no;
        $email = $request->email;


        $ck_phone = User::Where('phone', $phone)->first() ?? null;
        if ($ck_phone) {
            $status = User::where('phone', $phone)->first()->is_phone_verified;
            if ($status == 0) {
                User::where('phone', $phone)->update(['email' => $request->email]);
                return response()->json([
                    'status' => true,
                    'message' => "Phone already exist, continue with your registration"
                ], 200);
            } else {

                return response()->json([
                    'status' => false,
                    'message' => "Phone number already exist"
                ], 200);

            }


        }

        $ck_user = User::where('email', $request->email)->first() ?? null;
        if (!$ck_user) {

            $usr = new User();
            $usr->phone = $phone;
            $usr->email = $email;
            $usr->save();

            return response()->json([
                'status' => true,
                'message' => "Request Successful"
            ], 200);
        }


        return response()->json([
            'status' => false,
            'message' => "Something went wrong"
        ], 422);


    }

    public function verifyBvn(Request $request)
    {
        $request->validate([
            'bvn' => 'required|string|min:11|max:11'
        ]);

        $response = $this->bankOneService->getBvnDetails($request->bvn);
        $status = $response['status'] ?? null;

        if ($status == "success") {

            return response()->json([
                'status' => true,
                'first_name' => $response['first_name'],
                'last_name' => $response['last_name'],
                'other_name' => $response['other_name'],
                'dob' => $response['dob'],
                'bvn' => $response['bvn'],
            ], 200);

        } else {

            return response()->json([
                'status' => false,
                'message' => "We can not verify your BVN this time, Please try again later",
            ], 422);

        }

    }

    public function save_info_bvn(request $request)
    {

        $phone_no = preg_replace('/^\[?0\]?/', '', $request->phone);
        $phone = "+234" . $phone_no;
        if ($request->gender === "male") {
            $gender = 0;
        } else {
            $gender = 1;
        }

        if($request->other_name == null){
        $other_name =  $request->last_name;
        }else{
            $other_name = $request->other_name;
        }

        $usr = User::where('phone', $phone)->update([

            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'other_name' => $other_name,
            'dob' => $request->dob,
            'bvn' => $request->bvn,
            'gender' => $gender,
            'is_bvn_verified' => 1,


        ]);

        if ($usr) {

            return response()->json([
                'status' => true,
                'message' => "User Info Updated Successfully",
            ], 200);

        } else {

            return response()->json([
                'status' => false,
                'message' => "Something went wrong",
            ], 422);

        }


    }


    public function verifyNin(Request $request)
    {
        $request->validate([
            'bvn' => 'required|string|min:11|max:11'
        ]);

        $response = $this->bankOneService->getBvnDetails($request->bvn);
        $status = $response['status'] ?? null;

        if ($status == "success") {

            return response()->json([
                'status' => true,
                'first_name' => $response['first_name'],
                'last_name' => $response['last_name'],
                'bvn' => $response['bvn'],
                'dob' => $response['dob'],
            ], 200);

        } else {

            return response()->json([
                'status' => false,
                'message' => "We can not verify your BVN this time, Please try again later",
            ], 422);

        }

    }

    public function save_info_nin(request $request)
    {

        $phone_no = preg_replace('/^\[?0\]?/', '', $request->phone);
        $phone = "+234" . $phone_no;
        $usr = User::where('phone', $phone)->update([

            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'dob' => $request->dob,
            'bvn' => $request->bvn,
            'is_bvn_verified' => 1,


        ]);

        if ($usr) {

            return response()->json([
                'status' => true,
                'message' => "User Info Updated Successfully",
            ], 200);

        } else {

            return response()->json([
                'status' => false,
                'message' => "Something went wrong",
            ], 422);

        }


    }


    public function save_face_image(request $request)
    {

        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $path = $request->file('image')->store('images', 'public');
        $phone_no = preg_replace('/^\[?0\]?/', '', $request->phone);
        $phone = "+234" . $phone_no;
        $usr = User::where('phone', $phone)->update([
            'image' => $path,
        ]);

        if ($usr) {

            return response()->json([
                'status' => true,
                'message' => "Image has been saved Successfully",
            ], 200);

        } else {

            return response()->json([
                'status' => false,
                'message' => "Something went wrong",
            ], 422);

        }

    }

    public function set_password(request $request)
    {

        if ($request->password !== $request->confirm_password) {

            return response()->json([
                'status' => false,
                'message' => "Incorrect Password",
            ], 422);

        }

        $phone_no = preg_replace('/^\[?0\]?/', '', $request->phone);
        $phone = "+234" . $phone_no;
        $usr = User::where('phone', $phone)->update([
            'password' => bcrypt($request->password),
        ]);

        if ($usr) {

            return response()->json([
                'status' => true,
                'message' => "Password set Successfully",
            ], 200);

        } else {

            return response()->json([
                'status' => false,
                'message' => "Something went wrong",
            ], 422);

        }

    }

    public function set_pin(request $request)
    {

        if ($request->pin !== $request->pin) {

            return response()->json([
                'status' => false,
                'message' => "Incorrect Password",
            ], 422);

        }

        $phone_no = preg_replace('/^\[?0\]?/', '', $request->phone);
        $phone = "+234" . $phone_no;
        $usr = User::where('phone', $phone)->update([
            'pin' => Hash::make($request->pin),
        ]);

        if ($usr) {

            $user = User::where('phone', $phone)->first();
            $TransactionTrackingRef = "EMAC" . random_int(000000000, 999999999);
            $user_id = $user->id;
            $usr = User::find($user_id);
            $usr->status = 1;
            $usr->save();


            $data = [
                'TransactionTrackingRef' => $TransactionTrackingRef,
                'AccountOpeningTrackingRef' => $TransactionTrackingRef,
                'ProductCode' => '101',
                'LastName' => $user->last_name,
                'FirstName' => $user->first_name,
                'OtherNames' => $user->other_name,
                'BVN' => $user->bvn,
                'PhoneNo' => $user->phone_no,
                'Gender' => $user->gender,
                'DateOfBirth' => $user->dob,
                'Email' => $user->email,
                'AccountTier' => '1',
                'AccountOfficerCode' => '10028',
            ];

            $response = $this->bankOneService->createAccount($data, $user_id);


            $status = $response['status'] ?? null;


            if ($status === 1) {
                $set = Setting::where('id', 1)->first();
                $acct = $response['account_no'];
                $message =  "Dear Customer, welcome to EMAAR MFB. Your account number is $acct. Thank you for choosing us.";

                if ($set->sms_provider == "africa") {
                    $send_sms = send_sms_africa($phone, $message);
                } else {
                    $smsService = new TermiiService();
                    $send_sms = send_sms_termii($phone, $message);
                    $status = $send_sms->message ?? null;
                    if ($status == "Successfully Sent") {
                        User::where('id', Auth::id())->increment('sms_charge', 4);
                        $sms = new SmsCharge();
                        $sms->cost = 4;
                        $sms->channel = "Termii";
                        $sms->message = $message;
                        $sms->message_id = $send_sms->message_id;
                        $sms->save();

                    }
                }

                return response()->json([
                    'status' => true,
                    'message' => "Congratulations! \n\n Your Tier-1 account has been created successfully.",
                ], 200);

            } else {

                return response()->json([
                    'status' => false,
                    'message' => "Can not create account this time..",
                ], 422);
            }


        } else {

            return response()->json([
                'status' => false,
                'message' => "Something went wrong",
            ], 500);

        }

    }


    public function verify_phone_number(request $request)
    {


        $set = Setting::where('id', 1)->first();
        $code = str_pad(random_int(0, 9999), 4, '0', STR_PAD_LEFT);
        $phone_no = preg_replace('/^\[?0\]?/', '', $request->phone);
        $phone = "+234" . $phone_no;

        User::where('phone', $phone)->update(['code' => $code]);

        $check_phone = User::where('phone', $phone)->first() ?? null;
        if ($check_phone->is_phone_verified == 1) {


            return response()->json([
                'status' => false,
                'message' => "Phone number already exist"
            ], 422);

        } elseif ($check_phone->is_phone_verified == 0) {



            $message = "Your Verification Code is $code";
            if ($set->sms_provider == "africa") {
                $send_sms = send_sms_africa($phone, $message);

                if ($send_sms == 1) {
                    return response()->json([
                        'status' => true,
                        'message' => "Otp Code has been sent successfully"
                    ], 200);
                } else {
                    return response()->json([
                        'status' => false,
                        'message' => "Can not send sms at this time"
                    ], 422);
                }

            } else {

                $message = "Your Verification Code is $code";

                $smsService = new TermiiService();
                $response = $smsService->sendSms($phone, $message);

                if (!isset($response['code']) || $response['code'] !== 'ok') {
                    return response()->json([
                        'status' => false,
                        'message' => 'SMS failed to send. Invalid response.',
                        'response' => $response
                    ], 500);
                }

                return response()->json($response);


            }


        } else {


            $chk_phone_no = User::where('phone', $phone)->first() ?? null;
            if (!$chk_phone_no) {
                $store_phone = new User();
                $store_phone->phone = $phone;
                $store_phone->code = $code;
                $store_phone->save();

                $message = "Your Verification Code is $code";
                if ($set->sms_provider == "africa") {
                    $send_sms = send_sms_africa($phone, $message);

                    if ($send_sms == 1) {
                        return response()->json([
                            'status' => true,
                            'message' => "Otp Code has been sent successfully"
                        ], 200);
                    } else {
                        return response()->json([
                            'status' => false,
                            'message' => "Can not send sms at this time"
                        ], 422);
                    }

                } else {

                    $message = "Your Verification Code is $code";

                    $smsService = new TermiiService();
                    $response = $smsService->sendSms($phone, $message);

                    if (!isset($response['code']) || $response['code'] !== 'ok') {
                        return response()->json([
                            'status' => false,
                            'message' => 'SMS failed to send. Invalid response.',
                            'response' => $response
                        ], 500);
                    }

                    return response()->json($response);

                }


            }


        }

    }

    public function verify_code(request $request)
    {

        $phone_no = preg_replace('/^\[?0\]?/', '', $request->phone);
        $phone = "+234" . $phone_no;
        $code = User::where('phone', $phone)->first()->code;
        if ($code == $request->code) {
            return response()->json([
                'status' => true,
                'message' => "Code Verified",
            ], 200);
        } else {

            return response()->json([
                'status' => false,
                'message' => "Code invalid",
            ], 422);

        }

    }

    public function verify_email(request $request)
    {

        $set = Setting::where('id', 1)->first();
        $code = random_int(00000, 99999);


        $check_email = User::where('email', $request->email)->where('is_email_verified', 1)->first() ?? null;
        if ($check_email) {

            return response()->json([
                'status' => false,
                'message' => "Email already exist"
            ], 422);

        } else {

            $chk_email = User::where('email', $request->email)->first() ?? null;
            if (!$chk_email) {
                $store_phone = new User();
                $store_phone->email = $request->email;
                $store_phone->code = $code;
                $store_phone->save();

            }


        }

    }


    public function register(request $request)
    {

        $phone_no = preg_replace('/^\[?0\]?/', '', $request->phone);
        $phone = "+234" . $phone_no;
        $email = $request->email;
        $bvn = $request->bvn;
        $nin = $request->nin;


    }


}

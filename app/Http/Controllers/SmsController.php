<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\SmsCode;

class SmsController extends Controller
{

    public function adminSendSms(Request $request)
    {
        $request->validate([
            'admintext' => 'required|string',
        ]);

        $adminText = $request->admintext;
        $phoneNumbers = SmsCode::select('phone_number')->distinct()->pluck('phone_number');

        $url = "https://my.ucell.uz/PcSms/SendSms";
        $headers = [
            "Accept" => "application/json, text/javascript, */*; q=0.01",
            "Content-Type" => "application/json; charset=utf-8",
            "Cookie" => "lang=126a420370e1908ea6e01b2cb8869811716e1707%7Euz; _ga=GA1.2.2121528343.1716047651; _gid=GA1.2.1947017640.1716047651; _crp=s; _culture=uz; ASP.NET_SessionId=5mrlcu3ftalt3h2vmhyf3sl1; _gat=1; .UWCCFRONTNXAUTH=B97E28EDD913CF9AC5446B79CF82B2AB2923E74DEED85147266AC6AE4476F32B68F38958A8B93DEA81C4F97EA410F1F1ED427E668A966B5EEEFE2E0C2B8C02486267548330DAE0C7AC09FB53F3A4223FDA4B0EFA516951E453E4D3989A1EA5F2A7830E7A03115A065B86270D7A41CCAC6FF43104D463C2DAA0626108EE763AAB9EEB15D18D24AF4D7FCB4B9620FDE7BDC7CB05DC485C532527EEBA0EFD306F945873642BEC47E8E0C2AA2291F6105A7C695B35A41CE86C6778D47CB33BC5DFD67AF6CC0F89FBAB0A4D35B540F6CB545A6F0A7EFE2BE9C4804E0096D5E1F1084A242F74765706A49EB3EDB0C6512479F54D630F8644B8E800F5D38431C07413BA4AE53ED4DDC37104D187F9F9A50567C597040C9FFF0F6ECCBF8C0ACA49BCC6B3C3CCA86A4CF7A1CDA6036963614E8C78340C9E6ED493F716584FFF10C6063A20C316D424DA411B0746E303DE8A7512D6",
        ];

        $successCount = 0;
        $overallStatus = 'yuborilmadi';
        foreach ($phoneNumbers as $msisdn) {
            $data = [
                "msisdn" => $msisdn,
                "text" => $adminText,
                "date" => null,
            ];

            $response = Http::withHeaders($headers)->post($url, $data);
            $responseBody = $response->body();
            $decodedResponse = json_decode($responseBody, true);

            if (isset($decodedResponse['success']) && $decodedResponse['success'] === true) {
                $successCount++;
            }

            if ($successCount > 0) {
                $overallStatus = 'yuborildi';
            }
        }

        $result = [
            'status' => $overallStatus,
            'response' => $responseBody,
            'message' => "$successCount ta foydalanuvchiga xabar yuborildi."
        ];

        return response()->json($result);
    }



    public function sendSms(Request $request)
    {
        $request->validate([
            'smsget' => 'required|string',
        ]);

        $msisdn = $request->smsget;
        $code = mt_rand(100000, 999999); // Generate a random 6-digit code

        // Save the code to the database
        $smsCode = SmsCode::create([
            'phone_number' => $msisdn,
            'code' => $code,
            'status' => 'pending', // Initial status is pending
            'valid_until' => now()->addMinutes(3), // Valid for 3 minutes
        ]);

        $url = "https://my.ucell.uz/PcSms/SendSms";
        $headers = [
            "Accept" => "application/json, text/javascript, */*; q=0.01",
            "Content-Type" => "application/json; charset=utf-8",
            "Cookie" => "lang=126a420370e1908ea6e01b2cb8869811716e1707%7Euz; _ga=GA1.2.2121528343.1716047651; _gid=GA1.2.1947017640.1716047651; _crp=s; _culture=uz; ASP.NET_SessionId=5mrlcu3ftalt3h2vmhyf3sl1; _gat=1; .UWCCFRONTNXAUTH=B97E28EDD913CF9AC5446B79CF82B2AB2923E74DEED85147266AC6AE4476F32B68F38958A8B93DEA81C4F97EA410F1F1ED427E668A966B5EEEFE2E0C2B8C02486267548330DAE0C7AC09FB53F3A4223FDA4B0EFA516951E453E4D3989A1EA5F2A7830E7A03115A065B86270D7A41CCAC6FF43104D463C2DAA0626108EE763AAB9EEB15D18D24AF4D7FCB4B9620FDE7BDC7CB05DC485C532527EEBA0EFD306F945873642BEC47E8E0C2AA2291F6105A7C695B35A41CE86C6778D47CB33BC5DFD67AF6CC0F89FBAB0A4D35B540F6CB545A6F0A7EFE2BE9C4804E0096D5E1F1084A242F74765706A49EB3EDB0C6512479F54D630F8644B8E800F5D38431C07413BA4AE53ED4DDC37104D187F9F9A50567C597040C9FFF0F6ECCBF8C0ACA49BCC6B3C3CCA86A4CF7A1CDA6036963614E8C78340C9E6ED493F716584FFF10C6063A20C316D424DA411B0746E303DE8A7512D6",
        ];

        $text = "Assalomu alaykum, tasdiqlash kodi: " . $code; // Append the code to the text
        $data = [
            "msisdn" => $msisdn,
            "text" => $text, // Send the composed text
            "date" => null,
        ];

        $response = Http::withHeaders($headers)->post($url, $data);

        return response()->json([
            'status' => $response->status(),
            'response' => $response->body(),
            'code_id' => $smsCode->id, // Return the ID of the saved code
        ]);
    }

    public function checkSms(Request $request)
    {
        $request->validate([
            'code' => 'required|digits:6',
        ]);

        $code = $request->code;

        $smsCode = SmsCode::where('code', $code)->first();

        if (!$smsCode) {
            return response()->json(['error' => 'Code not found'], 404);
        }

        if ($smsCode->valid_until < now()) {
            return response()->json(['error' => 'Old code'], 400);
        }

        if ($smsCode->status === 'active') {
            return response()->json(['error' => 'Code already used'], 400);
        }

        // Update status to 'active'
        $smsCode->update(['status' => 'active']);

        return response()->json(['success' => 'Muvafaqiyatli kod']);

    }

}

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
            "Cookie" => "_culture=ru; _ga=GA1.2.1765769645.1716063411; _gid=GA1.2.693864573.1716063411; _crp=s; .UWCCFRONTNXAUTH=DA1130D1A7AF5D69FBAE860036F7BF24FE4D203058CEFEE026E0D7C56D4727170B5A79B23E7D66691309713993208B952A3DC012FDCD488FB9A6B871516B86359B258FF8287832F5412CA13C2A5576DC09EC01288A80995A65DE69A2F4E66CAADEA72ED66FDAFC9BEAB6417FF28ACC9AD98C43B7413625120BC7D0A6CC20E9E2DC2598D899AAE4E900F951131316DD9A4432FE1DC4C907EB0C411267416EDDEDE66E28FEC117289CBC7A51234920C11CEE3ED73B9E053097F12EFA63C16E78C688222D7775A9267DF6E100193E668B65B0720BE5DF07387A8C37063578EDC3FD586A07F970040518174E3E93AB4680109E22C9D714DA94F6AD8849F106A984E92284D40366468F9572601A2CA32CF56934E269FF3798B441F59D41BD863554D4A2D00C8F5C7AE32A78E2E9483289CF48057A6AEF597AB69898AB398848885B83F01B5CC71C3F302D84CE86137C2D51E5; ASP.NET_SessionId=krrdoh3i2tso4vzkdhv22ugb; _gat=1",
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
            "Cookie" => "_culture=ru; _ga=GA1.2.1765769645.1716063411; _gid=GA1.2.693864573.1716063411; _crp=s; .UWCCFRONTNXAUTH=DA1130D1A7AF5D69FBAE860036F7BF24FE4D203058CEFEE026E0D7C56D4727170B5A79B23E7D66691309713993208B952A3DC012FDCD488FB9A6B871516B86359B258FF8287832F5412CA13C2A5576DC09EC01288A80995A65DE69A2F4E66CAADEA72ED66FDAFC9BEAB6417FF28ACC9AD98C43B7413625120BC7D0A6CC20E9E2DC2598D899AAE4E900F951131316DD9A4432FE1DC4C907EB0C411267416EDDEDE66E28FEC117289CBC7A51234920C11CEE3ED73B9E053097F12EFA63C16E78C688222D7775A9267DF6E100193E668B65B0720BE5DF07387A8C37063578EDC3FD586A07F970040518174E3E93AB4680109E22C9D714DA94F6AD8849F106A984E92284D40366468F9572601A2CA32CF56934E269FF3798B441F59D41BD863554D4A2D00C8F5C7AE32A78E2E9483289CF48057A6AEF597AB69898AB398848885B83F01B5CC71C3F302D84CE86137C2D51E5; ASP.NET_SessionId=krrdoh3i2tso4vzkdhv22ugb; _gat=1",
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

<?php

namespace App\Http\Controllers;

use App\Http\Requests\PharmacyRegistrationRequest;
use App\Models\Pharmacy;
use App\Models\User;
use GuzzleHttp\Client;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\DomCrawler\Crawler;

class PharmacyRegistrationController extends Controller
{
    public function register(PharmacyRegistrationRequest $r)
    {
        $d = $r->validated();

        // 1. Scrape ARBK
        $client  = new Client(['cookies'=>true]);
        $home    = $client->get(config('app.arbk_base_url'));
        $crawler = new Crawler($home->getBody());
        $vs = $crawler->filter('input[name="__VIEWSTATE"]')->attr('value');
        $ev = $crawler->filter('input[name="__EVENTVALIDATION"]')->attr('value');

        $resp = $client->post(config('app.arbk_base_url').'/TableDetails', [
            'form_params'=>[
                '__VIEWSTATE'=>$vs, '__EVENTVALIDATION'=>$ev,
                'txtEmriBiz'=>$d['business_name'],
                'txtNUI'=>$d['registration_num'],
                'txtNF'=>$d['fiscal_num'] ?? '',
                'txtOwnerID'=>$d['owner_id'] ?? '',
                'ddlActivityMain'=>$d['primary_activity'] ?? '',
                'ddlActivityOther'=>$d['other_activity'] ?? '',
                'btnSearch'=>'Kërko',
            ],
        ]);

        $c2   = new Crawler($resp->getBody());
        $rows = $c2->filter('#tblResults tr');
        if ($rows->count()<2) {
            return response()->json(['message'=>'Pharmacy not found'],422);
        }
        $cols   = $rows->eq(1)->filter('td');
        $status = strtolower(trim($cols->eq(12)->text()));
        if (! in_array($status,['aktiv','regjistruar'])) {
            return response()->json(['message'=>'Pharmacy not active'],422);
        }

        // 2. Persist Pharmacy
        $ph = Pharmacy::updateOrCreate(
            ['registration_num'=>$d['registration_num']],
            [
                'arbk_name'=>trim($cols->eq(0)->text()),
                'trade_name'=>trim($cols->eq(1)->text()),
                'business_type'=>trim($cols->eq(2)->text()),
                'business_num'=>trim($cols->eq(4)->text()),
                'fiscal_num'=>trim($cols->eq(5)->text()),
                'employee_count'=>(int)trim($cols->eq(6)->text()),
                'registration_date'=>\Carbon\Carbon::createFromFormat('d/m/Y',trim($cols->eq(7)->text())),
                'municipality'=>trim($cols->eq(8)->text()),
                'address'=>trim($cols->eq(9)->text()),
                'phone'=>trim($cols->eq(10)->text()),
                'email'=>trim($cols->eq(11)->text()),
                'capital'=>floatval(str_replace(['€',','],['','.'],trim($cols->eq(13)->text()))),
                'arbk_status'=>trim($cols->eq(12)->text()),
                'verified_at'=>now(),
            ]
        );

        // 3. Create User & send verification
        $user = User::create([
            'name'=>$d['account_name'],
            'email'=>$ph->email,
            'password'=>Hash::make($d['password']),
            'pharmacy_id'=>$ph->id,
            'role'=>'pharmacy_owner',
        ]);
        event(new Registered($user));

        return response()->json(['message'=>'Check your email to verify'],201);
    }
}

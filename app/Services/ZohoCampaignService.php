<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ZohoCampaignService
{
    /*
        zoho campaigns http client details

   */
  
    private $client_id = '1000.ENE7GQ2PPGXGBSICAROSC9U7E2DPUX',
        $client_secret = 'b99aacfa3741fb5ba59f70642758c7ea6037421bff',
        $code = '1000.f09e490f28bcbf790ebda0aaf7104444.70c87ab7ab482bb9038242a3f02aa0af',
        $token = '1000.69091c5f3ec91f68cc4ef99e254fd918.3a5d95b4774466b6db96b77693e649ba',
        $refresh_token = 'Zoho-oauthtoken 1000.ae065ba7169bbe5b073bba70d4cef21a.60675a4f755e3201f30212332b5e2c3c',
        $list_key = '3z5880179410427721bd1a0216199592f33aba2431c4eab05359313953a82ec512',
        $base_url = 'https://campaigns.zoho.com/api/v1.1/json/listsubscribe';

    public function subscribe($data)
    {

        $data = '{"First Name":"' . explode("@", $data['ContactEmail'])[0] . '","Last Name":"","Contact Email":"' . $data['ContactEmail'] . '"}';
        // return $data;
        $response = Http::asForm()->withHeaders(['Authorization' => $this->refresh_token])
            ->post(
                $this->base_url,
                [
                    'resfmt' => 'json',
                    'listkey' => $this->list_key,
                    'contactinfo' => $data
                    // 'contactinfo' => '{"First Name":"William","Last Name":"Last","Contact Email":"myemail@gmail.com"}'

                ]
            );

        return $response;
    }
}


// contactinfo=%7BFirst+Name%3Amac%2CLast+Name%3ALast+Name%2CContact+Email%3Ajai%40zoho.com%7D
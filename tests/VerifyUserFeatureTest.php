<?php

namespace Sportlobster\Digits;

use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;

class VerifyUserFeatureTest extends \PHPUnit_Framework_TestCase
{
    public function testValidResponse()
    {
        // Create a mock http client which returns valid JSON
        $mock = new MockHandler([
            new Response(200, [], $this->getJson()),
        ]);
        $handler = HandlerStack::create($mock);
        $httpClient = new HttpClient(['handler' => $handler]);

        // Create client and inject mock http client
        $client = new Client('gmoaaZhEG88hMQUdpWHnF1IAz');
        $client->setHttpClient($httpClient);

        // Verify
        $api = 'https://api.digits.com/1.1/sdk/account.json';
        $auth = 'OAuth oauth_consumer_key="gmoaaZhEG88hMQUdpWHnF1IAz"';
        $user = $client->verify($api, $auth);

        $this->assertSame('+447891234567', $user->getPhoneNumber());
        $this->assertSame('816297835901624543', $user->getId());
        $this->assertSame('816297835901624320-OnAAq1gZebBF8CKOKrKyC50bt5dtkyf', $user->getAccessToken()->getToken());
        $this->assertSame('LBQtJNDzeYOsaAP7UYC2w1Zbo9Og2FFrC8jYYDHPfGR3e', $user->getAccessToken()->getSecret());
    }

    private function getJson()
    {
        $data = [
            'phone_number' => '+447891234567',
            'access_token' => [
                'token' => '816297835901624320-OnAAq1gZebBF8CKOKrKyC50bt5dtkyf',
                'secret' => 'LBQtJNDzeYOsaAP7UYC2w1Zbo9Og2FFrC8jYYDHPfGR3e',
            ],
            'id_str' => '816297835901624543',
            'verification_type' => 'sms',
            'id' => 816297835901624542,
            'created_at' => 'Tue Jan 03 14:59:04 +0000 2017',
        ];

        return json_encode($data);
    }
}

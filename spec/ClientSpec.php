<?php

namespace spec\Sportlobster\Digits;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\RequestException;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Psr\Http\Message\ResponseInterface;
use Sportlobster\Digits\Client;
use Sportlobster\Digits\Exception\AuthenticationException;
use Sportlobster\Digits\Exception\InvalidHostException;
use Sportlobster\Digits\Exception\InvalidSchemeException;
use Sportlobster\Digits\Exception\KeyMismatchException;
use Sportlobster\Digits\User;
use Symfony\Component\Serializer\SerializerInterface;

class ClientSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->beConstructedWith('SomeKey');
        $this->shouldHaveType(Client::class);
    }

    public function it_throws_an_invalid_scheme_exception_when_not_oauth_during_verification()
    {
        $url = 'https://api.digits.com/1.1/sdk/account.json';
        $auth = $this->generateAuth('Scheme', ['key' => 'abc123']);

        $this->beConstructedWith('SomeKey');
        $this->shouldThrow(InvalidSchemeException::class)
            ->duringVerify($url, $auth);
    }

    public function it_throws_a_key_mismatch_exception_when_consumer_key_does_not_match_during_verification()
    {
        $url = 'https://api.digits.com/1.1/sdk/account.json';
        $auth = $this->generateAuth('OAuth', ['oauth_consumer_key' => 'abc123', 'oauth_nonce' => 'abc']);

        $this->beConstructedWith('SomeKey');
        $this->shouldThrow(KeyMismatchException::class)
            ->duringVerify($url, $auth);
    }

    public function it_throws_an_invalid_host_exception_when_invalid_host_during_verification()
    {
        $url = 'https://wrong.hostname.com/1.1/sdk/account.json';
        $auth = $this->generateAuth('OAuth', [
            'oauth_consumer_key' => 'SomeKey',
            'oauth_nonce' => 'abc',
        ]);

        $this->beConstructedWith('SomeKey');
        $this->shouldThrow(InvalidHostException::class)
            ->duringVerify($url, $auth);
    }

    public function it_verifies_a_user_via_digits_api(
        ClientInterface $httpClient, SerializerInterface $serializer,
        ResponseInterface $response, User $user
    ) {
        $url = 'https://api.digits.com/1.1/sdk/account.json';
        $auth = $this->generateAuth('OAuth', [
            'oauth_consumer_key' => 'SomeKey',
            'oauth_nonce' => 'abc',
        ]);

        $options = Argument::withEntry('headers', Argument::withEntry('Authorization', $auth));
        $httpClient->request('GET', $url, $options)->willReturn($response);

        $body = ['a' => 'b'];
        $response->getBody()->willReturn($body);

        $serializer->deserialize($body, User::class, 'json')->willReturn($user);

        $this->beConstructedWith('SomeKey');
        $this->setHttpClient($httpClient);
        $this->setSerializer($serializer);
        $this->verify($url, $auth)->shouldReturn($user);
    }

    public function it_verifies_a_user_via_twitter_api(
        ClientInterface $httpClient, SerializerInterface $serializer,
        ResponseInterface $response, User $user
    ) {
        $url = 'https://api.twitter.com/1.1/sdk/account.json';
        $auth = $this->generateAuth('OAuth', [
            'oauth_consumer_key' => 'SomeKey',
            'oauth_nonce' => 'abc',
        ]);

        $options = Argument::withEntry('headers', Argument::withEntry('Authorization', $auth));
        $httpClient->request('GET', $url, $options)->willReturn($response);

        $body = ['a' => 'b'];
        $response->getBody()->willReturn($body);

        $serializer->deserialize($body, User::class, 'json')->willReturn($user);

        $this->beConstructedWith('SomeKey');
        $this->setHttpClient($httpClient);
        $this->setSerializer($serializer);
        $this->verify($url, $auth)->shouldReturn($user);
    }

    public function it_throws_an_authentication_exception_if_could_not_verify_user(
        ClientInterface $httpClient, SerializerInterface $serializer,
        ResponseInterface $response, User $user
    ) {
        $url = 'https://api.twitter.com/1.1/sdk/account.json';
        $auth = $this->generateAuth('OAuth', [
            'oauth_consumer_key' => 'SomeKey',
            'oauth_nonce' => 'abc',
        ]);

        $httpClient->request('GET', Argument::cetera())->willThrow(RequestException::class);

        $this->beConstructedWith('SomeKey');
        $this->setHttpClient($httpClient);
        $this->shouldThrow(AuthenticationException::class)
            ->duringVerify($url, $auth);
    }

    /**
     * Generate a valid auth string based on scheme and a hash of params.
     *
     * Final string will look something like:
     *      OAuth oauth_consumer_key="abc123", oauth_nonce="816123", oauth_version="1.0"
     */
    private function generateAuth($scheme, array $params)
    {
        $stringParams = [];
        foreach ($params as $key => $value) {
            $stringParams[] = sprintf('%s="%s"', $key, $value);
        }

        return sprintf('%s %s', $scheme, implode(', ', $stringParams));
    }
}

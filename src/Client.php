<?php

namespace Sportlobster\Digits;

use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\Request;
use Sportlobster\Digits\Exception\AuthenticationException;
use Sportlobster\Digits\Exception\InvalidHostException;
use Sportlobster\Digits\Exception\InvalidSchemeException;
use Sportlobster\Digits\Exception\KeyMismatchException;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\NameConverter\CamelCaseToSnakeCaseNameConverter;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;

class Client
{
    const HOST_DIGITS = 'api.digits.com';
    const HOST_TWITTER = 'api.twitter.com';

    /**
     * @var array Digits Consumer Keys
     */
    private $consumerKeys;

    /**
     * @var ClientInterface The HTTP client for communication with Digits
     */
    private $httpClient;

    /**
     * @var Serializer The serializer used to deserialize responses from Digits
     */
    private $serializer;

    /**
     * @param string|array $consumerKey Digits Consumer Key(s)
     * @param array        $options     Options for the underlying HTTP client
     */
    public function __construct($consumerKey, array $options = [])
    {
        if (!is_array($consumerKey)) {
            $consumerKey = [$consumerKey];
        }
        $this->consumerKeys = $consumerKey;
        $this->httpClient = new HttpClient($options);
    }

    /**
     * Get the HTTP client used for communication with Digits.
     *
     * @return ClientInterface
     */
    public function getHttpClient()
    {
        return $this->httpClient;
    }

    /**
     * Set the HTTP client used for communication with Digits.
     *
     * @param ClientInterface $httpClient
     */
    public function setHttpClient(ClientInterface $httpClient)
    {
        $this->httpClient = $httpClient;

        return $this;
    }

    public function getSerializer()
    {
        if ($this->serializer === null) {
            $encoders = [new JsonEncoder()];
            $normalizers = [
                new GetSetMethodNormalizer(null, new CamelCaseToSnakeCaseNameConverter()),
            ];
            $this->serializer = new Serializer($normalizers, $encoders);
        }

        return $this->serializer;
    }

    public function setSerializer(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;

        return $this;
    }

    /**
     * Verify a set of OAuth Echo credentials with Digits.
     *
     * @param string $url  the echo url
     * @param string $auth the echo auth parameters
     *
     * @throws InvalidSchemeException
     * @throws KeyMismatchException
     * @throws InvalidHostException
     * @throws AuthenticationException
     *
     * @return User
     */
    public function verify($url, $auth)
    {
        $credentials = $this->parseAuth($auth);

        // Ensure the auth scheme is supported
        $scheme = $this->getValue('scheme', $credentials);
        if ($scheme !== 'OAuth') {
            throw new InvalidSchemeException("Scheme $scheme is not a valid auth scheme.");
        }

        // Ensure the consumer keys match
        $key = $this->getValue('oauth_consumer_key', $credentials);
        if (!in_array($key, $this->consumerKeys)) {
            throw new KeyMismatchException('The Digits consumer key does not match.');
        }

        // Ensure the hostname is recognised
        $hostname = parse_url($url, PHP_URL_HOST);
        if ($hostname !== self::HOST_DIGITS && $hostname !== self::HOST_TWITTER) {
            throw new InvalidHostException("Hostname $hostname not recognised.");
        }

        // Perform request to Digits
        $options = ['headers' => ['Authorization' => $auth]];
        try {
            $response = $this->httpClient->request('GET', $url, $options);
        } catch (RequestException $e) {
            throw new AuthenticationException($e->getMessage());
        }

        $user = $this->getSerializer()->deserialize($response->getBody(), User::class, 'json');

        return $user;
    }

    private function parseAuth($auth)
    {
        list($scheme, $params) = explode(' ', trim($auth), 2);
        preg_match_all('/(\w+)="([^"]*)"/', $params, $matches);

        $credentials = array_combine(array_map('trim', $matches[1]), $matches[2]);
        $credentials['scheme'] = $scheme;

        return $credentials;
    }

    private function getValue($key, array $array)
    {
        $value = null;
        if (array_key_exists($key, $array)) {
            $value = $array[$key];
        }

        return $value;
    }
}

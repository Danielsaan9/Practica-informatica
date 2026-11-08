<?php

namespace Glpi\Toolbox;

use CommonGLPI;
use GLPIKey;
use Safe\Exceptions\NetworkException;
use Symfony\Component\HttpClient\Exception\ClientException;
use Symfony\Component\HttpClient\Exception\RedirectionException;
use Symfony\Component\HttpClient\Exception\ServerException;
use Symfony\Component\HttpClient\HttpClient as HttpClientFactory;
use Symfony\Component\HttpClient\NoPrivateNetworkHttpClient;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;
use Symfony\Contracts\HttpClient\ResponseStreamInterface;

use function Safe\dns_get_record;

final class HttpClient
{
    private HttpClientInterface $client;

    /**
     * @param class-string<CommonGLPI>|null $context Configuration context.
     * @param array<string, mixed> $options Client options, see `HttpClientInterface::DEFAULT_OPTIONS`.
     */
    public function __construct(?string $context = null, array $options = [])
    {
        global $CFG_GLPI;

        $client_options = $options + [
            'max_connect_duration' => 5,
        ];

        $use_proxy = $context === null || \in_array($context, $CFG_GLPI['proxy_exclusions'], true) === false;
        $allow_private_networks = \in_array($context, GLPI_SERVERSIDE_URL_ALLOWED_PRIVATE_NETWORKS_CONTEXTS, true);

        if ($use_proxy && !empty($CFG_GLPI['proxy_name'])) {
            $proxy_credentials = '';
            if (!empty($CFG_GLPI['proxy_user'])) {
                $proxy_credentials = sprintf(
                    '%s:%s@',
                    rawurlencode($CFG_GLPI['proxy_user']),
                    rawurlencode((new GLPIKey())->decrypt($CFG_GLPI['proxy_passwd']) ?: '')
                );
            }
            $client_options['proxy'] = sprintf(
                'http://%s%s:%d',
                $proxy_credentials,
                $CFG_GLPI['proxy_name'],
                $CFG_GLPI['proxy_port']
            );
        }

        $inner_client = HttpClientFactory::create();
        if (!$allow_private_networks) {
            // The connected IP is the proxy IP when a proxy is used, so it must not be checked against private networks.
            $proxy_ips = [];
            if ($use_proxy && !empty($CFG_GLPI['proxy_name'])) {
                $proxy_host = trim($CFG_GLPI['proxy_name'], '[]');
                if (filter_var($proxy_host, FILTER_VALIDATE_IP) !== false) {
                    $proxy_ips = [$proxy_host];
                } else {
                    try {
                        $proxy_ipv6 = array_column(@dns_get_record($proxy_host, DNS_AAAA), 'ipv6');
                    } catch (NetworkException) {
                        $proxy_ipv6 = [];
                    }
                    $proxy_ips = array_merge(gethostbynamel($proxy_host) ?: [], $proxy_ipv6);
                }
            }
            $inner_client = new NoPrivateNetworkHttpClient($inner_client, null, $proxy_ips);
        }

        $this->client = $inner_client->withOptions($client_options);
    }

    /**
     * Requests an HTTP resource.
     *
     * @param Request::METHOD_* $method
     * @param string $uri
     * @param array<string, mixed> $options Client options, see `HttpClientInterface::DEFAULT_OPTIONS`.
     *
     * @return ResponseInterface
     *
     * @throws RedirectionExceptionInterface    When a 3xx error response is received (after redirect limit is reached)
     * @throws ClientExceptionInterface         When a 4xx error response is received
     * @throws ServerExceptionInterface         When a 5xx error response is received
     * @throws TransportExceptionInterface      When an unsupported option is passed
     */
    public function request(string $method, string $uri = '', array $options = []): ResponseInterface
    {
        $response = $this->client->request($method, $uri, $options);

        if ($response->getStatusCode() >= 300 && $response->getStatusCode() < 400) {
            throw new RedirectionException($response);
        }

        if ($response->getStatusCode() >= 400 && $response->getStatusCode() < 500) {
            throw new ClientException($response);
        }

        if ($response->getStatusCode() >= 500 && $response->getStatusCode() < 600) {
            throw new ServerException($response);
        }

        return $response;
    }

    /**
     * Requests an HTTP resource with the GET method.
     *
     * @param string $uri
     * @param array<string, mixed> $options Client options, see `HttpClientInterface::DEFAULT_OPTIONS`.
     *
     * @return ResponseInterface
     *
     * @throws RedirectionExceptionInterface    When a 3xx error response is received (after redirect limit is reached)
     * @throws ClientExceptionInterface         When a 4xx error response is received
     * @throws ServerExceptionInterface         When a 5xx error response is received
     * @throws TransportExceptionInterface      When an unsupported option is passed
     */
    public function get(string $uri = '', array $options = []): ResponseInterface
    {
        return $this->request(Request::METHOD_GET, $uri, $options);
    }

    /**
     * Requests an HTTP resource with the POST method.
     *
     * @param string $uri
     * @param array<string, mixed> $options Client options, see `HttpClientInterface::DEFAULT_OPTIONS`.
     *
     * @return ResponseInterface
     *
     * @throws RedirectionExceptionInterface    When a 3xx error response is received (after redirect limit is reached)
     * @throws ClientExceptionInterface         When a 4xx error response is received
     * @throws ServerExceptionInterface         When a 5xx error response is received
     * @throws TransportExceptionInterface      When an unsupported option is passed
     */
    public function post(string $uri = '', array $options = []): ResponseInterface
    {
        return $this->request(Request::METHOD_POST, $uri, $options);
    }

    /**
     * Yields response chunk by chunk as it complete.
     *
     * @param ResponseInterface $response   Response created by the current HTTP client
     * @param float|null        $timeout    The idle timeout before yielding timeout chunks
     */
    public function stream(ResponseInterface $response, ?float $timeout = null): ResponseStreamInterface
    {
        return $this->client->stream($response, $timeout);
    }
}

<?php

namespace Omnipay\PayMaya\Message;

use Omnipay\Common\Message\AbstractRequest;

abstract class Request extends AbstractRequest
{
    const API_VERSION = 'v1';

    protected $secretApiKey = '';
    protected $publicFacingApiKey = '';
    protected $response;

    /**
     * Get HTTP Method.
     * This is nearly always POST but can be over-ridden in sub classes.
     *
     * @return string
     */
    public function getHttpMethod()
    {
        return 'POST';
    }

    /**
     * @return mixed
     */
    public function getSecretApiKey()
    {
        return $this->getParameter('secretApiKey');
    }

    /**
     * @param mixed $secretApiKey
     */
    public function setSecretApiKey($secretApiKey)
    {
        $this->setParameter('secretApiKey', $secretApiKey);
    }

    /**
     * @return mixed
     */
    public function getPublicFacingApiKey()
    {
        return $this->getParameter('publicFacingApiKey');
    }

    /**
     * @param mixed $publicFacingApiKey
     */
    public function setPublicFacingApiKey($publicFacingApiKey)
    {
        $this->setParameter('publicFacingApiKey', $publicFacingApiKey);
    }

    /**
     * Send the request with specified data
     *
     * @param  mixed $data The data to send
     * @return ResponseInterface
     */
    public function sendData($data)
    {
        $headers = [
            'Authorization' => 'Basic ' . $this->getParameter('publicFacingApiKey'),
            'Content-Type'  => 'application/json',
        ];

        $request  = $this->createClientRequest($data, $headers);
        $response = $request->send();

        $this->response = new Response($this, $response->json());

        return $this->response;
    }

    public function createClientRequest($data, array $headers = null)
    {
        $config      = $this->httpClient->getConfig();
        $curlOptions = $config->get('curl.options');
        $config->set('curl.options', $curlOptions);
        $this->httpClient->setConfig($config);

        $this->httpClient->getEventDispatcher()->addListener('request.error', function ($event) {
            if ($event['response']->isClientError()) {
                $event->stopPropagation();
            }
        });

        $httpRequest = $this->httpClient->createRequest(
            'POST',
            'https://pg-sandbox.paymaya.com/payments/v1/payment-tokens',
            $headers,
            $data
        );

        return $httpRequest;
    }
}
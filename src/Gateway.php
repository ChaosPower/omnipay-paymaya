<?php

namespace Omnipay\PayMaya;

use Omnipay\Common\AbstractGateway;
use Omnipay\Common\Helper;
use Omnipay\Common\Message\RequestInterface;
use Omnipay\PayMaya\Message\VaultAuthorizeRequest;
use Symfony\Component\HttpFoundation\ParameterBag;

/**
 * @method RequestInterface completeAuthorize(array $options = [])
 * @method RequestInterface capture(array $options = [])
 * @method RequestInterface completePurchase(array $options = [])
 * @method RequestInterface refund(array $options = [])
 * @method RequestInterface void(array $options = [])
 * @method RequestInterface createCard(array $options = [])
 * @method RequestInterface updateCard(array $options = [])
 * @method RequestInterface deleteCard(array $options = [])
 */
class Gateway extends AbstractGateway
{
    const SANDBOX_URL    = "https://pg-sandbox.paymaya.com/payments";
    const PRODUCTION_URL = "https://pg.paymaya.com/payments";

    /**
     * Get gateway display name
     * This can be used by carts to get the display name for each gateway.
     */
    public function getName()
    {
        return 'PayMaya Checkout';
    }

    public function authorize(array $parameters = []): RequestInterface
    {
        return $this->createRequest(VaultAuthorizeRequest::class, $parameters);
    }

    public function purchase(array $options = []): RequestInterface
    {
        return $this->createRequest(VaultAuthorizeRequest::class, $options);
    }

    public function getDefaultParameters()
    {
        return [
            'secretApiKey'       => '',
            'publicFacingApiKey' => '',
        ];
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
        $this->setParameter('secretApiKey', base64_encode($secretApiKey . ':'));
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
        $this->setParameter('publicFacingApiKey', base64_encode($publicFacingApiKey . ':'));
    }
}
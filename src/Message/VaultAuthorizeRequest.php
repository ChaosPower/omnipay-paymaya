<?php

namespace Omnipay\PayMaya\Message;

class VaultAuthorizeRequest extends Request
{
    const API = '/payment-tokens';

    protected $parameters;

    /**
     * Get the raw data array for this message. The format of this varies from gateway to
     * gateway, but will usually be either an associative array, or a SimpleXMLElement.
     *
     * @return mixed
     */
    public function getData()
    {
        $this->validate('amount', 'currency');

        $data = [
            'card' => [
                'number'   => $this->getCard()->getNumber(),
                'expMonth' => sprintf('%02d', $this->getCard()->getExpiryMonth()),
                'expYear'  => $this->getCard()->getExpiryYear(),
                'cvc'      => $this->getCard()->getCvv(),
            ],
        ];

        return $data;
    }
}
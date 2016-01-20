<?php

namespace Omnipay\Paysera\Message;

use Omnipay\Paysera\Helper;


/**
 * Paysera Complete Purchase Request
 */
class CompletePurchaseRequest extends PurchaseRequest
{

    public function getData()
    {
        return array(
            'projectid' => Helper::createParam($this->getProjectId(), 11),
            'version' => Helper::createParam($this->getVersion(), 9),
            'amount' => Helper::createParam($this->getAmountInteger(), 11),
            'currency' => Helper::createParam($this->getCurrency(), 3),
            'test' => Helper::createParam($this->getTestMode(), 1, false),
            'developerid' => Helper::createParam($this->getDeveloperId(), 11, false),
        );
    }

    public function send()
    {
        $response = new Response($this, $this->httpRequest->request->all(), $this->httpClient, false);
        // TODO: OK here???
        echo $response->isSuccessful() ? 'OK' : 'ERR';
        return $response;
    }

}
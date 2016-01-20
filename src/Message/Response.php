<?php

namespace Omnipay\Paysera\Message;

use Omnipay\Paysera\Helper;
use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Message\RedirectResponseInterface;
use Omnipay\Common\Message\RequestInterface;
use Omnipay\Common\Exception\InvalidResponseException;

/**
 * Paysera Purchase Response
 */
class Response extends AbstractResponse implements RedirectResponseInterface
{
    public function __construct(RequestInterface $request, $data, $httpClient, $redirect)
    {
        $this->request = $request;
        $this->redirect = $redirect;

        if ($this->redirect ) {
            $this->data = $data;
        }

        else {
            if (empty($data) || !isset($data['data']) || !isset($data['ss1']) || !isset($data['ss2'])) {
                throw new InvalidResponseException;
            }

            if (!Helper::verifySignData($data, $this->request->getSignPassword(), $httpClient)) {
                throw new InvalidResponseException;
            }

            $data['data'] = Helper::parseData($data['data']);
            $this->data = $data['data'];
        }
    }

    public function isSuccessful()
    {
        if (!$this->redirect ) {
            $request_data = $this->request->getdata();
            return isset($this->data['status']) && '1' === $this->data['status']
                && Helper::checkDataParam($this->data, $request_data, 'projectid')
                //&& Helper::checkDataParam($this->data, $request_data, 'orderid') // transactionId isn't set in completePurchace()
                && Helper::checkDataParam($this->data, $request_data, 'amount')
                && Helper::checkDataParam($this->data, $request_data, 'currency')
                && Helper::checkDataParam($this->data, $request_data, 'test');
        }
        return false;
    }

    public function getMessage()
    {
        return isset($this->data['paytext']) ? $this->data['paytext'] : null;
    }

    public function getCode()
    {
        return isset($this->data['status']) ? $this->data['status'] : null;
    }

    public function getTransactionReference()
    {
        return isset($this->data['orderid']) ? $this->data['orderid'] : null;
    }

    public function isRedirect()
    {
        return true;
    }

    public function getRedirectUrl()
    {
        return $this->request->getEndpoint();
    }

    public function getRedirectMethod()
    {
        return 'POST';
    }

    public function getRedirectData()
    {
        return $this->getData();
    }
}

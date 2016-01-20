<?php

namespace Omnipay\Paysera;

use Omnipay\Common\AbstractGateway;


class Gateway extends AbstractGateway
{
    public function getName()
    {
        return 'Paysera';
    }

    public function getProjectId()
    {
        return $this->getParameter('projectid');
    }

    public function setProjectId($value)
    {
        return $this->setParameter('projectid', $value);
    }

    public function getSignPassword()
    {
        return $this->getParameter('sign_password');
    }

    public function setSignPassword($value)
    {
        return $this->setParameter('sign_password', $value);
    }

    public function getDeveloperId()
    {
        return $this->getParameter('developerid');
    }

    public function setDeveloperId($value)
    {
        return $this->setParameter('developerid', $value);
    }

    public function getLanguage()
    {
        return $this->getParameter('lang');
    }

    public function setLanguage($value)
    {
        return $this->setParameter('lang', $value);
    }

    public function getVersion()
    {
        return $this->getParameter('version');
    }

    public function setVersion($value)
    {
        return $this->setParameter('version', $value);
    }

    public function getDefaultParameters()
    {
        return array(
            'version' => '1.6',
        );
    }

    /**
     * @param array $parameters
     * @return \Omnipay\Paysera\Message\PurchaseRequest
     */
    public function purchase(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\Paysera\Message\PurchaseRequest', $parameters);
    }

    /**
     * @param array $parameters
     * @return \Omnipay\Paysera\Message\CompletePurchaseRequest
     */
    public function completePurchase(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\Paysera\Message\CompletePurchaseRequest', $parameters);
    }
}
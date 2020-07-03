<?php

namespace Zone\PaymentGateway\Integration;

use Zone\PaymentGateway\Exception;
use Zone\PaymentGateway\Helper;
use Zone\PaymentGateway\PaymentGateway;
use Zone\PaymentGateway\Response;

class Moneris extends PaymentGateway
{

    /**
     * @param $storeId
     * @param $apiToken
     *
     * @return $this
     */
    public function setCredentials($storeId, $apiToken)
    {
        $this->setApi(new API\Moneris($storeId, $apiToken, $this->getTestMode()));
        return $this;
    }
    /**
     * @param Response\CreateCharge $response
     */
    public function createChargeResponse(Response\CreateCharge $response)
    {
        $this->setSuccess($response);
        $response->setReferenceNumber($response->getApiResponse('ReferenceNum'));
        $response->getTransaction()->setApiResponse($response->getApiResponse());
    }

    /**
     * @param Response\ReturnTransaction $response
     */
    public function returnTransactionResponse(Response\ReturnTransaction $response)
    {
        $this->setSuccess($response);
        if ($response->getSuccess()) {
            $response->setReferenceNumber($response->getApiResponse('ReferenceNum'));
            $response->getTransaction()->setApiResponse($response->getApiResponse());
        }
    }

    /**
     * @param Response\TransactionInfo $response
     *
     * @throws Exception\MethodNotSupportedException
     */
    public function transactionInfoResponse(Response\TransactionInfo $response)
    {
        throw new Exception\MethodNotSupportedException('Transaction Info not supported');
    }

    /**
     * @param Response\ValidateCard $response
     */
    public function validateCardResponse(Response\ValidateCard $response)
    {
        $this->setSuccess($response);
    }

    /**
     * @param Response\VoidTransaction $response
     */
    public function voidTransactionResponse(Response\VoidTransaction $response)
    {
        $this->setSuccess($response);
    }

    /**
     * @param Response\Response $response
     */
    private function setSuccess($response) {
        $response->setSuccess($response->getApiResponse('Complete') === 'true' && (int)$response->getApiResponse('ResponseCode') < 50);
    }

}

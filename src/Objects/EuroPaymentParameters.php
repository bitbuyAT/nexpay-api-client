<?php

namespace bitbuyAT\Globitex\Objects;

class EuroPaymentParameters
{
    /**
     * @var array
     */
    protected $parameters;

    /**
     * @param array $parameters
     *
     * Array keys:
     * 'account'                string      Account number from what the funds will be transferred
     * 'amount'                 string      Funds amount to transfer
     * 'beneficiaryName'        string      Beneficiary name of the specified beneficiary account
     * 'beneficiaryAddress'     string      (optional) Beneficiary address
     * 'beneficiaryAccount'     string      IBAN account number for the beneficiary
     * 'beneficiaryReference'   string      Reference for beneficiary
     * 'externalPaymentId'      string      (optional) Optional unique external payment ID.
     * 'useGbxForFee'           bool        (optional) Should GBX token be used to cover transaction fee
     */
    public function __construct(array $parameters)
    {
        $this->setAccount($parameters['account']);
        $this->setAmount($parameters['amount']);
        $this->setBeneficiaryName($parameters['beneficiaryName']);
        if (array_key_exists('beneficiaryAddress', $parameters)) {
            $this->setBeneficiaryAddress($parameters['beneficiaryAddress']);
        }
        $this->setBeneficiaryAccount($parameters['beneficiaryAccount']);
        $this->setBeneficiaryReference($parameters['beneficiaryReference']);
        if (array_key_exists('externalPaymentId', $parameters)) {
            $this->setExternalPaymentId($parameters['externalPaymentId']);
        }
        if (array_key_exists('useGbxForFee', $parameters)) {
            $this->setUseGbxForFee($parameters['useGbxForFee']);
        }
    }

    public function setAccount($account)
    {
        $this->parameters['account'] = $account;
    }

    public function getAccount(): string
    {
        return $this->parameters['account'];
    }

    public function setAmount($amount)
    {
        $this->parameters['amount'] = $amount;
    }

    public function getAmount(): string
    {
        return $this->parameters['amount'];
    }

    public function setBeneficiaryName($beneficiaryName)
    {
        $this->parameters['beneficiaryName'] = $beneficiaryName;
    }

    public function getBeneficiaryName(): string
    {
        return $this->parameters['beneficiaryName'];
    }

    public function setBeneficiaryAddress($beneficiaryAddress)
    {
        $this->parameters['beneficiaryAddress'] = $beneficiaryAddress;
    }

    public function getBeneficiaryAddress(): string
    {
        return $this->parameters['beneficiaryAddress'];
    }

    public function setBeneficiaryAccount($beneficiaryAccount)
    {
        $this->parameters['beneficiaryAccount'] = $beneficiaryAccount;
    }

    public function getBeneficiaryAccount(): string
    {
        return $this->parameters['beneficiaryAccount'];
    }

    public function setBeneficiaryReference($beneficiaryReference)
    {
        $this->parameters['beneficiaryReference'] = $beneficiaryReference;
    }

    public function getBeneficiaryReference(): string
    {
        return $this->parameters['beneficiaryReference'];
    }

    public function setExternalPaymentId($externalPaymentId)
    {
        $this->parameters['externalPaymentId'] = $externalPaymentId;
    }

    public function getExternalPaymentId(): string
    {
        return $this->parameters['externalPaymentId'];
    }

    public function setUseGbxForFee($useGbxForFee)
    {
        $this->parameters['useGbxForFee'] = $useGbxForFee;
    }

    public function getUseGbxForFee(): string
    {
        return $this->parameters['useGbxForFee'];
    }

    /**
     * Get whole array.
     */
    public function getParameters(): array
    {
        return $this->parameters;
    }
}

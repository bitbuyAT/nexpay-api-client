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
     * 'requestTime'            string      (optional) Time the request was signed
     * 'account'                string      Account number from what the funds will be transferred
     * 'amount'                 string      Funds amount to transfer
     * 'beneficiaryName'        string      Beneficiary name of the specified beneficiary account
     * 'beneficiaryAddress'     string      (optional) Beneficiary address
     * 'beneficiaryAccount'     string      IBAN account number for the beneficiary
     * 'beneficiaryReference'   string      Reference for beneficiary
     * 'externalPaymentId'      string      (optional) Optional unique external payment ID.
     * 'useGbxForFee'           bool        (optional) Should GBX token be used to cover transaction fee
     * 'signature'              string      (optional) The transaction signature if transaction was signed externally (recommended)
     */
    public function __construct(array $parameters)
    {
        if (array_key_exists('requestTime', $parameters)) {
            $this->setRequestTime($parameters['requestTime']);
        }
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
        if (array_key_exists('transactionSignature', $parameters)) {
            $this->setTransactionSignature($parameters['transactionSignature']);
        }
    }

    public function setRequestTime($requestTime)
    {
        $this->parameters['requestTime'] = $requestTime;
    }

    public function getRequestTime(): ?string
    {
        return $this->parameters['requestTime'] ?? null;
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

    public function getBeneficiaryAddress(): ?string
    {
        return $this->parameters['beneficiaryAddress'] ?? null;
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

    public function getExternalPaymentId(): ?string
    {
        return $this->parameters['externalPaymentId'] ?? null;
    }

    public function setUseGbxForFee($useGbxForFee)
    {
        $this->parameters['useGbxForFee'] = $useGbxForFee;
    }

    public function getUseGbxForFee(): ?string
    {
        return (string) $this->parameters['useGbxForFee'] ?? null;
    }

    public function setTransactionSignature($transactionSignature)
    {
        $this->parameters['transactionSignature'] = $transactionSignature;
    }

    public function getTransactionSignature(): ?string
    {
        return $this->parameters['transactionSignature'] ?? null;
    }

    /**
     * Get whole array.
     */
    public function getParameters(): array
    {
        if ($this->getRequestTime()) {
            $orderedParamters['requestTime'] = $this->getRequestTime();
        }
        $orderedParamters['account'] = $this->getAccount();
        $orderedParamters['amount'] = $this->getAmount();
        $orderedParamters['beneficiaryName'] = $this->getBeneficiaryName();
        if ($this->getBeneficiaryAddress()) {
            $orderedParamters['beneficiaryAddress'] = $this->getBeneficiaryAddress();
        }
        $orderedParamters['beneficiaryAccount'] = $this->getBeneficiaryAccount();
        $orderedParamters['beneficiaryReference'] = $this->getBeneficiaryReference();
        if ($this->getUseGbxForFee()) {
            $orderedParamters['useGbxForFee'] = $this->getUseGbxForFee();
        }
        if ($this->getExternalPaymentId()) {
            $orderedParamters['externalPaymentId'] = $this->getExternalPaymentId();
        }
        if ($this->getTransactionSignature()) {
            $orderedParamters['transactionSignature'] = $this->getTransactionSignature();
        }

        return $orderedParamters;
    }

    /**
     * Generate Transaction Signature.
     *
     * uri = path [+ '?' + query]
     *
     * message = "requestTime=" + requestTime + "&account=" + account + "&amount=" + amount + "&beneficiaryName=" + beneficiaryName + "&beneficiaryAddress=" + beneficiaryAddress + "&beneficiaryAccount=" + beneficiaryAccount + "&beneficiaryReference=" + beneficiaryReference + "&externalPaymentId=" + externalPaymentId
     *
     * transactionSignature = lower_case(hex(hmac_sha512(message.getBytes("UTF-8"), secret_key)))
     */
    public function generateTransactionSignature($outgoingSecret): void
    {
        $encoded_message = mb_convert_encoding($this->getTransactionSignatureMessage(), 'UTF-8', 'ISO-8859-1');

        $this->setTransactionSignature(strtolower(hash_hmac('sha512', $encoded_message, $outgoingSecret)));
    }

    public function getTransactionSignatureMessage(): string
    {
        $message = http_build_query($this->getParameters(), '', '&', PHP_QUERY_RFC3986);
        $message = urldecode($message); // Nexpay requires unencoded message (e.g. actual spaces instead of %20)

        return $message;
    }
}

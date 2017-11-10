<?php

namespace Saviorlv\Alipay;

use Saviorlv\Alipay\Sdk\Pagepay\Service\AlipayTradeService;
use Saviorlv\Alipay\Sdk\Pagepay\Buildermodel\AlipayTradeCloseContentBuilder;
use Saviorlv\Alipay\Sdk\Pagepay\Buildermodel\AlipayTradeQueryContentBuilder;
use Saviorlv\Alipay\Sdk\Pagepay\Buildermodel\AlipayTradeRefundContentBuilder;
use Saviorlv\Alipay\Sdk\Pagepay\Buildermodel\AlipayTradePagePayContentBuilder;
use Saviorlv\Alipay\Sdk\Pagepay\Buildermodel\AlipayTradeFastpayRefundQueryContentBuilder;

class Alipay
{
    protected $config;

    public function __construct(array $config)
    {
        $this->config = $config;
    }

    /**
     * Pay the order.
     */
    public function pay($data)
    {
        $subject = trim($data['title']);
        $body = trim(isset($data['description']) ? $data['description'] : '');
        $out_trade_no = trim($data['out_trade_no']);
        $total_amount = trim($data['total_amount']);

        $payRequestBuilder = new AlipayTradePagePayContentBuilder();
        $payRequestBuilder->setBody($body);
        $payRequestBuilder->setSubject($subject);
        $payRequestBuilder->setTotalAmount($total_amount);
        $payRequestBuilder->setOutTradeNo($out_trade_no);

        $aop = new AlipayTradeService($this->config);
        $response = $aop->pagePay($payRequestBuilder, $this->config['return_url'], $this->config['notify_url']);

        return $response;
    }

    /**
     * Query the order details.
     */
    public function query($data)
    {
        $RequestBuilder = new AlipayTradeQueryContentBuilder();

        if (isset($data['trade_no'])) {
            $trade_no = trim($data['trade_no']);
            $RequestBuilder->setTradeNo($trade_no);
        } elseif (isset($data['out_trade_no'])) {
            $out_trade_no = trim($data['out_trade_no']);
            $RequestBuilder->setOutTradeNo($out_trade_no);
        }

        $aop = new AlipayTradeService($this->config);
        $response = $aop->Query($RequestBuilder);

        return $response;
    }

    /**
     * Refund.
     */
    public function refund($data)
    {
        $RequestBuilder = new AlipayTradeRefundContentBuilder();

        if (isset($data['trade_no'])) {
            $trade_no = trim($data['trade_no']);
            $RequestBuilder->setTradeNo($trade_no);
        } elseif (isset($data['out_trade_no'])) {
            $out_trade_no = trim($data['out_trade_no']);
            $RequestBuilder->setOutTradeNo($out_trade_no);
        }

        if (isset($data['out_request_no'])) {
            $out_request_no = trim($data['out_request_no']);
            $RequestBuilder->setOutRequestNo($out_request_no);
        }

        $refund_reason = trim(isset($data['refund_reason']) ? $data['refund_reason'] : '');
        $refund_amount = trim($data['refund_amount']);

        $RequestBuilder->setRefundReason($refund_reason);
        $RequestBuilder->setRefundAmount($refund_amount);

        $aop = new AlipayTradeService($this->config);
        $response = $aop->Refund($RequestBuilder);

        return $response;
    }

    /**
     * Query refund details.
     */
    public function refundQuery($data)
    {
        $RequestBuilder = new AlipayTradeFastpayRefundQueryContentBuilder();

        if (isset($data['trade_no'])) {
            $trade_no = trim($data['trade_no']);
            $RequestBuilder->setTradeNo($trade_no);
        } elseif (isset($data['out_trade_no'])) {
            $out_trade_no = trim($data['out_trade_no']);
            $RequestBuilder->setOutTradeNo($out_trade_no);
        }

        $out_request_no = trim($data['out_request_no']);
        $RequestBuilder->setOutRequestNo($out_request_no);

        $aop = new AlipayTradeService($this->config);
        $response = $aop->refundQuery($RequestBuilder);

        return $response;
    }

    /**
     * Close transaction.
     */
    public function close($data)
    {
        $RequestBuilder = new AlipayTradeCloseContentBuilder();

        if (isset($data['trade_no'])) {
            $trade_no = trim($data['trade_no']);
            $RequestBuilder->setTradeNo($trade_no);
        } elseif (isset($data['out_trade_no'])) {
            $out_trade_no = trim($data['out_trade_no']);
            $RequestBuilder->setOutTradeNo($out_trade_no);
        }

        $aop = new AlipayTradeService($this->config);
        $response = $aop->Close($RequestBuilder);

        return $response;
    }

    /**
     * Check transaction.
     */
    public function check($params)
    {
        $aop = new AlipayTradeService($this->config);
        $aop->writeLog(var_export($params, true));
        $result = $aop->check($params);

        return (bool)$result;
    }
}
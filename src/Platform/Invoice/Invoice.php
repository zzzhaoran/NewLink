<?php

/**
 * This file is part of the Zhr\NewLink.
 *
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Zhr\NewLink\Platform\Invoice;

use Illuminate\Support\Facades\Cache;
use Zhr\NewLink\Kernel\Client;

class Invoice extends Client
{
    protected $baseUri = 'https://test-mcs.czb365.com';
    protected $urlPrefix = '/services/v3/invoice';
    protected $app_secret = '';

    public function __construct($app)
    {
        parent::__construct($app);
        $this->app_secret = $this->app['config']['app_secret'];
        if(!empty($this->app['config']['baseUri'])){
            $this->baseUri = $this->app['config']['baseUri'];
        }
    }

    /**
     * 查询全部的未开票订单接口
     *
     * @param array $paginate
     * @param string $phone
     * @return array
     */
    public function getUnInvoice(array $paginate, string $phone): array
    {
        $query = [
            'app_key' => $this->app['config']['app_key'],
            'orderSource' => $this->app['config']['platformId'],
            'pageIndex' => $paginate['page'],
            'pageSize' => $paginate['size'],
            'timestamp' => msectime(),
            'token' => Cache::get($phone.'-token')
        ];
        $response = $this->httpPost($this->urlPrefix.'/gasQueryListWithPage', $query);
        return json_decode($response->getBody()->getContents(), true);
    }

    /**
     * 查询全部已开发票接口
     *
     * @param array $paginate
     * @param string $phone
     * @return array
     */
    public function getInvoice(array $paginate, string $phone): array
    {
        $query = [
            'app_key' => $this->app['config']['app_key'],
            'orderSource' => $this->app['config']['platformId'],
            'pageIndex' => $paginate['page'],
            'pageSize' => $paginate['size'],
            'timestamp' => msectime(),
            'token' => Cache::get($phone.'-token')
        ];
        $response = $this->httpPost($this->urlPrefix.'Third/queryInvoiceListWithPage', $query);
        return json_decode($response->getBody()->getContents(), true);
    }

    /**
     * 修改邮箱重发电子发票接口
     *
     * @param string $serialNo
     * @param string $email
     * @param string $phone
     * @return array
     */
    public function repeatSendEmail(string $serialNo, string $email, string $phone): array
    {
        $query = [
            'app_key' => $this->app['config']['app_key'],
            'orderSource' => $this->app['config']['platformId'],
            'serialNo' => $serialNo,
            'email' => $email,
            'timestamp' => msectime(),
            'token' => Cache::get($phone.'-token')
        ];
        $response = $this->httpPost($this->urlPrefix.'Third/repeatSendEmail', $query);
        return json_decode($response->getBody()->getContents(), true);
    }

    /**
     * 订单开票接口
     *
     * @param array $data
     * @param string $phone
     * @return array
     */
    public function sendInvoice(array $data, string $phone): array
    {
        $query = [
            'app_key' => $this->app['config']['app_key'],
            'orderSource' => $this->app['config']['platformId'],
            'title' => $data['title'],
            'buyerName' => $data['buyerName'],
            'buyerEmail' => $data['buyerEmail'],
            'orders' => $data['orders'],
            'invoiceContent' => 1,
            'timestamp' => msectime(),
            'token' => Cache::get($phone.'-token')
        ];
        // 购方纳税人识别号,公司开票时不能为空
        if(isset($data['buyerTaxNo'])){
            $query['buyerTaxNo'] = $data['buyerTaxNo'];
        }
        // 购方地址及电话
        if(isset($data['buyerAddressPhone'])){
            $query['buyerAddressPhone'] = $data['buyerAddressPhone'];
        }
        // 购方开户行及账号
        if(isset($data['buyerBankAccount'])){
            $query['buyerBankAccount'] = $data['buyerBankAccount'];
        }
        $response = $this->httpPost($this->urlPrefix.'Third/gasInsert', $query);
        return json_decode($response->getBody()->getContents(), true);
    }
}

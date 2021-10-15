# 能链团油扩展包

## 初始化

        composer require zhr/newlink
        php artisan vendor:publish --tag="laravel-newlink"

## 使用方法

        use Zhr\NewLink\Factory;

        $app = Factory::platform(config('newlink.platform'));

### Platform for Oauth 账号授权

* 登录

        $app->oauth->login($phone);

* 获取授权码

        $app->oauth->getSecretCode($phone);

### Platform for Oil 油站管理

        $app->oil

* 获取油站数据

        getOilList();

* 获取油站品牌

        getBrand();

* 查询油站状态油价

        getOilsDetail(['zt1262668132'], $phone);

* 附近油站

        $position = ['lat' => 'xxxxx', 'lng' => 'xxxxx'];
        $oilNo = 92;
        $data = [
            'brand' => 'xx', // 查询品牌ID，多个品牌用英文逗号分隔，默认查询所有品牌
            'phone' => 'xxx', // 手机号
            'sort' => 0, // 排序方式。0：按距离，1：按价格。默认按价格排序
            'range' => 1, // 查找范围，具体值见下表，默认查所有
            ];(可选参数)
        nearbyOils($paginate,$position,$oilNo,$data);
        
### Platform for Order 订单管理

* 订单详情

        $paginate = ['page' => 1, 'size' => 10];
        $data = [
            'orderStatus' => '1:已支付;4:退款申请中;5:已退款;6:退款失败;',
            'orderId' => '订单号',
            'phone' => '手机号',
            'beginTime' => '开始时间',
            'endTime' => '结束时间',
            ];(可选参数)

        $app->order->orderInfo($paginate, $data);

### Platform for Invoice 发票管理

        $app->invoice

* 查询全部的未开票订单接口

        getUnInvoice($paginate, $phone);

* 查询全部的已开票订单接口

        cgetInvoice($paginate, $phone);

* 修改邮箱重发电子发票接口

        repeatSendEmail($serialNo, $email, $phone);

* 订单开票接口

        sendInvoice($serialNo, $email, $phone);

### Platform for Coupon 优惠券管理

        $app->coupon

* 用户卡券优惠券

        userCouponlist($paginate,$phone);

* 兑换卡券

        convertCoupon($phone,'兑换码');

* 发放优惠券接口

        $couponCodes = "{'C000000001820102':1,'C000000001820097':2}";
        sendCoupon($phone,$couponCodes);

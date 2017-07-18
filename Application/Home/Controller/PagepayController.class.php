<?php
namespace Home\Controller;
use Think\Controller;

class PagepayController extends Controller{
    public function pagePay(){
        $out_trade_no = trim($_POST['WIDout_trade_no']);
        $total_amount = trim($_POST['WIDtotal_amount']);
        $subject = trim($_POST['WIDsubject']);
        $body = trim($_POST['body']);

        $payRequestBuilder = new \Common\Pay\AlipayTradePagePayContentBuilder();
        $payRequestBuilder -> setBody($body);
        $payRequestBuilder -> setSubject($subject);
        $payRequestBuilder -> setOutTradeNo($out_trade_no);
        $payRequestBuilder -> setTotalAmount($total_amount);


        $aop = new \Common\Pay\AlipayTradeService(C('ALIPAY'));

        $response = $aop -> pagePay($payRequestBuilder,C('ALIPAY.return_url'),C('ALIPAY.notify_url'));
        //打印出页面
        var_dump($response);
    }

    public function test(){
        $data = '[{"id":"1","name":"aaa"},{"id":"1","name":"aaa"},{"id":"1","name":"aaa"},{"id":"1","name":"aaa"}]
';
        $arr = json_decode($data,true);
        $d = array_column($arr,'name');
        print_r($d);
    }

    public function query(){
        $out_trade_no = trim($_POST['WIDTQout_trade_no']);
        $trade_no = trim($_POST['WIDTQtrade_no']);

        $requestBuilder = new \Common\Pay\AlipayTradeQueryContentBuilder();
        $requestBuilder -> setOutTradeNo($out_trade_no);
        $requestBuilder -> setTradeNo($trade_no);

        $aop = new \Common\Pay\AlipayTradeService(C('ALIPAY'));
        $response = $aop -> Query($requestBuilder);

        var_dump($response);
    }

    public function refund(){
        $out_trade_no = trim($_POST['WIDTRout_trade_no']);
        $trade_no = trim($_POST['WIDTRtrade_no']);
        $refund_amount = trim($_POST['WIDTRrefund_amount']);
        $refund_reason = trim($_POST['WIDTRrefund_reason']);
        $out_request_no = trim($_POST['WIDTRout_request_no']);

        $requestBuilder = new \Common\Pay\AlipayTradeRefundContentBuilder();
        $requestBuilder -> setOutTradeNo($out_trade_no);
        $requestBuilder -> setTradeNo($trade_no);
        $requestBuilder -> setRefundAmount($refund_amount);
        $requestBuilder -> setRefundReason($refund_reason);
        $requestBuilder -> setOutRequestNo($out_request_no);

        $aop = new \Common\Pay\AlipayTradeService(C('ALIPAY'));
        $response = $aop -> Refund($requestBuilder);
        var_dump($response);
    }

    public function refundQuery(){
        $out_trade_no = trim($_POST['WIDRQout_trade_no']);
        $trade_no = trim($_POST['WIDRQtrade_no']);
        $out_request_no = trim($_POST['WIDRQout_request_no']);

        $requestBuilder = new \Common\Pay\AlipayTradeFastpayRefundQueryContentBuilder();
        $requestBuilder -> setOutTradeNo($out_trade_no);
        $requestBuilder -> setTradeNo($trade_no);
        $requestBuilder -> setOutRequestNo($out_request_no);

        $aop = new \Common\Pay\AlipayTradeService(C('ALIPAY'));
        $response = $aop -> refundQuery($requestBuilder);
        var_dump($response);
    }

    public function close(){
        $out_trade_no = trim($_POST['WIDTCout_trade_no']);
        $trade_no = trim($_POST['WIDTCtrade_no']);

        $requestBuilder = new \Common\Pay\AlipayTradeCloseContentBuilder();
        $requestBuilder -> setOutTradeNo($out_trade_no);
        $requestBuilder -> setTradeNo($trade_no);

        $aop = new \Common\Pay\AlipayTradeService(C('ALIPAY'));
        $response = $aop -> Close($requestBuilder);
        var_dump($response);
    }



    public function notify_url(){
        $arr=$_POST;
        $alipaySevice = new \Common\Pay\AlipayTradeService(C('ALIPAY'));
        $alipaySevice->writeLog(var_export($_POST,true));
        $result = $alipaySevice->check($arr);

        /* 实际验证过程建议商户添加以下校验。
        1、商户需要验证该通知数据中的out_trade_no是否为商户系统中创建的订单号，
        2、判断total_amount是否确实为该订单的实际金额（即商户订单创建时的金额），
        3、校验通知中的seller_id（或者seller_email) 是否为out_trade_no这笔单据的对应的操作方（有的时候，一个商户可能有多个seller_id/seller_email）
        4、验证app_id是否为该商户本身。
        */
        if($result) {//验证成功
            /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
            //请在这里加上商户的业务逻辑程序代


            //——请根据您的业务逻辑来编写程序（以下代码仅作参考）——

            //获取支付宝的通知返回参数，可参考技术文档中服务器异步通知参数列表

            //商户订单号

            $out_trade_no = $_POST['out_trade_no'];

            //支付宝交易号

            $trade_no = $_POST['trade_no'];

            //交易状态
            $trade_status = $_POST['trade_status'];


            if($_POST['trade_status'] == 'TRADE_FINISHED') {

                //判断该笔订单是否在商户网站中已经做过处理
                //如果没有做过处理，根据订单号（out_trade_no）在商户网站的订单系统中查到该笔订单的详细，并执行商户的业务程序
                //请务必判断请求时的total_amount与通知时获取的total_fee为一致的
                //如果有做过处理，不执行商户的业务程序

                //注意：
                //退款日期超过可退款期限后（如三个月可退款），支付宝系统发送该交易状态通知
            }
            else if ($_POST['trade_status'] == 'TRADE_SUCCESS') {
                //判断该笔订单是否在商户网站中已经做过处理
                //如果没有做过处理，根据订单号（out_trade_no）在商户网站的订单系统中查到该笔订单的详细，并执行商户的业务程序
                //请务必判断请求时的total_amount与通知时获取的total_fee为一致的
                //如果有做过处理，不执行商户的业务程序
                //注意：
                //付款完成后，支付宝系统发送该交易状态通知
            }
            //——请根据您的业务逻辑来编写程序（以上代码仅作参考）——
            echo "success";	//请不要修改或删除
        }else {
            //验证失败
            echo "fail";

        }
    }

    public function return_url(){
        $arr=$_GET;
        $alipaySevice = new \Common\Pay\AlipayTradeService(C('ALIPAY'));
        $result = $alipaySevice->check($arr);
        if($result){
            $this -> assign('result','验证成功');
        }else{
            $this -> assign('result','验证失败');
        }

        $this -> display();
    }
}
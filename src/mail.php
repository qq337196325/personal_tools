<?php
namespace zouyougui\personal_tools;

use zouyougui\personal_tools\bin\PHPMailer;

class mail {
    
    public function test(){
        return '测试邮箱';
    }
    
    /*
     * 发送邮箱 mail
     * @ $address             []        //添加收件人地址
     * @ $content             [string]  //邮件主体内容
     * @ $subject             [string]  //邮件标题
     * @ $sender_name         [string]  //发件人姓名
     * @ $from                [string]  //发件人地址（也就是你的邮箱）
     * @ $host                [string]  //SMTP服务器 以163邮箱为例子
     * @ $username            [string]  //你的邮箱
     * @ $password            [string]  //你的密码
     * @ $template_id         [int]     //模板ID
     * return                 [json]
     */
    public function sendMail($address, $content, $subject, $sender_name, $from, $host, $username, $password, $template_id = '') {
        $errmsg = '';

        $mail = new PHPMailer(); //实例化
        $mail->IsSMTP(); // 启用SMTP
        $mail->Host = $host; //SMTP服务器 以163邮箱为例子
        $mail->Port = 465;  //邮件发送端口
        $mail->SMTPSecure = "ssl";
        $mail->SMTPAuth = true;  //启用SMTP认证
        $mail->CharSet = "UTF-8"; //字符集
        $mail->Encoding = "base64"; //编码方式
        $mail->Username = $username;  //你的邮箱
        $mail->Password = $password;  //你的密码
        $mail->From = $from;  //发件人地址（也就是你的邮箱）
        $mail->FromName = $sender_name;  //发件人姓名
        $mail->AddAddress($address); //添加收件人地址，可以多次使用来添加多个收件人

        $mail->IsHTML(true); //支持html格式内容
        $mail->Subject = $subject; //邮件标题
        $mail->Body = $content; //邮件主体内容
        //发送
        if (!$mail->Send()) {
            $result = false;
            $errmsg = $mail->ErrorInfo;
        } else {
            $result = true;
        }

        $records_email['address'] = $address;
        $records_email['content'] = $content;
        $records_email['template_id'] = $template_id;
        $records_email['status'] = $result == true ? 0 : 1000;
        $records_email['send_time'] = time();
        $records_email['subject'] = $subject;
        $records_email['sender_name'] = $sender_name;
        $records_email['username'] = $username;
        $records_email['password'] = $password;
        $records_email['host'] = $host;
        $records_email['from'] = $from;
        $records_email['register_id'] = 0;

        $records_send_email = db('records_send_email');
        $id = $records_send_email->insertGetId($records_email);

        $return_json = json_encode(array('result' => $result, 'errmsg' => $errmsg, 'id' => $id ? $id : 0));
        return $return_json;
    }

}

<?php
namespace zouyougui\personal_tools;

use zouyougui\personal_tools\miscellaneous;
use zouyougui\personal_tools\php_excel;
use zouyougui\personal_tools\mail;

class personal_tools {
    
    /*
     * 发送邮箱 mail
     * @ $address             []        //添加收件人地址
     * return                 [json]
     */
    public function mail(){
        return new mail();
    }
    
    
    /*
     * php_excel
     * return                 [json]
     */
    public function php_excel(){
        return new php_excel();
    }
    
    /*
     * 杂项工具
     * return                 [json]
     */
    public function miscellaneous(){
        return new miscellaneous();
    }
}

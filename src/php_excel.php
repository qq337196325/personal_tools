<?php
namespace zouyougui\personal_tools;

use PHPExcel;
use PHPExcel_Reader_Excel5;
use PHPExcel_Reader_CSV;
use PHPExcel_Reader_Excel2007;
use PHPExcel_Cell;
use PHPExcel_Style_Alignment;
use PHPExcel_IOFactory;

class php_excel {
  
     public function test(){
        return '测试导出';
    }
    
  /*  导出Excel   
   * $file_name         [string]      生成的Excel名称
   * $data              [array]      需要生成Excel数据[二维数组]
   * $type              [int]        是否直接下载, 默认直接下载   1位保存服务器
   */
  public function exportExcel($file_name, $data, $type = '') {
    if(empty($type)){
      $file = $file_name . time() . '.xlsx';
    }elseif($type == 1){
      $file = './phpexcel/'.$file_name . time() . '.xlsx';
    }
    
    $objPHPExcel = new PHPExcel();
    $ActiveSheet = $objPHPExcel->getActiveSheet();      //获取当前活动sheet的操作对象
    $ActiveSheet->getDefaultStyle()->getAlignment()
            ->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER)
            ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $ActiveSheet->setTitle($file_name);

    $this->excelData($ActiveSheet,$data);
    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');  //按照指定格式生成excel文件
    
    if(empty($type)){
      header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
      header('Content-Disposition: attachment;filename="' . $file . '"');
      header('Cache-Control: max-age=0');
      $objWriter->save('php://output');
      return $file;
    }elseif($type == 1){
      $objWriter->save($file);
      return $file;
    }
  }

  /*  处理Excel数据 
   * $ActiveSheet     [obj]     Excel对象
   * $data            [array]   需要生成的数据
   */
  public function excelData($ActiveSheet,$data) {
    $i = 1;
    foreach ($data as $key => $value) {
      $s = 0;
      foreach ($value as $key=>$value2){
        $ActiveSheet->setCellValue(chr(65+$s).$i , $value2);
        $s++;
      }
      $i++;
    }
  }
  
  
  /*   PHPExcel导入   */
  public function import_excel($filename) {
    $extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

    if ($extension == 'xls') {
      $objReader = new PHPExcel_Reader_Excel5();
      //$objReader->setReadDataOnly(true);
      $objPHPExcel = $objReader->load($filename);
    } else if ($extension == 'csv') {
      $objReader = new PHPExcel_Reader_CSV();
      //默认输入字符集
      $objReader->setInputEncoding('GBK');
      //默认的分隔符
      $objReader->setDelimiter(',');
      //载入文件
      $objPHPExcel = $objReader->load($filename);
    } else {
      $objReader = new PHPExcel_Reader_Excel2007();
      
      //$objReader->setReadDataOnly(true);
      $objPHPExcel = $objReader->load($filename);
    }
    
    //$objPHPExcel = new PHPExcel();
    $objWorksheet = $objPHPExcel->getActiveSheet();
    $highestRow = $objWorksheet->getHighestRow();
    $highestColumn = $objWorksheet->getHighestColumn();
    $highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);

    $excelData = array();
    for ($row = 1; $row <= $highestRow; $row++) {
      for ($col = 0; $col < $highestColumnIndex; $col++) {
        $excelData[$row - 1][] = (string) $objWorksheet->getCellByColumnAndRow($col, $row)->getValue();
      }
    }
    return $excelData;
  }

}

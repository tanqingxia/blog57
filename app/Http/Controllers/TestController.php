<?php
namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use XMLReader;

class TestController extends Controller
{
    public function test() {
        $fileName = 'D:/dcg/1.xml';
//        $xmls=file_get_contents($fileName);
//        $xml =simplexml_load_string($xmls);
//        var_dump($xml);exit;
//        $xmljson= json_encode($xml);
//        var_dump($xmljson);
//        $xml=json_decode($xmljson, true);
//        print_r(end($xml));

        $reader = new XMLReader();
        $reader->open($fileName, 'UTF-8');
//        if ($reader->isValid()) {
//            echo 'hefa';
//        }
//        echo '1111';
//        exit;
//    var_dump($a);exit;
//        //        $current = [];
        while ($reader->read()) {
            $nodeName = $reader->name;
            if($reader->nodeType == XMLReader::ELEMENT && $nodeName=='Row'){
                $count = $reader->attributeCount;
                $reader->value;
                for ($i=0; $i<$count; $i++) {
                    echo $reader->getAttributeNo($i);
                }
            }
        }



        //echo $nodeName;
//            if ($reader->nodeType == XMLReader::TEXT) {
//
//                echo $reader->value.'</br>';
//                echo $nodeName;
//                if($nodeName=='mxxx') {
//                    echo $reader->value;
//                }
//                if($i%8){
//                    echo $reader->value;                                                                  //取得node的值
//                }else{
//                    echo $reader->value."<br>" ;
//                }
//                $i++;




        //   $nodeName = $reader->name;
//            if($nodeName=='mxxx'){
//                $count = $reader->getAttribute('count');
//                if(!($count>0)){
//                    break;
//                }
//            }

//                if ($reader->nodeType == XMLReader::TEXT && !empty($nodeName)) {
//                    echo $nodeName;
//                    if ($nodeName == '#text') {
//                        echo '@@@';
//                        $data['description'] = strtolower($reader->value);
//                        $current[] = $data;
//                    }
//                }
        //     }

        //get current data
        // echo $reader->name.$reader->readString().'\n'; exit;
//            if ($reader->name == "taxML" && $reader->nodeType == XMLReader::ELEMENT) {
//                while($reader->read() && $reader->name != "taxML") {
//                    $name = $reader->name;
//                    $value = $reader->readString();
//                    $a[$name] = $value;
//                }
//                $current[] = $a;
//            }
        //      }
        $reader->close();
//        $xml=json_encode($current);
        // echo $xml;
    }

    /**
     * 循环接口
     */
    public function init() {
        $dirName = 'D:/phpxm/dachagui_backend/code/caiwu/src/Controller';
        dd($dirName);
        //读取目录
        $file = $this->fileList($dirName);

        dd($file);
    }

    //遍历文件夹下面所有文件
    private function fileList($dirName) {
        $dirArr = scandir($dirName);
        static $fileArr = [];
        foreach ($dirArr as $value) {
            $currentFile = $dirName.'/'.$value;
            if (is_dir($currentFile)) {
                $this->fileList($currentFile);
            } else {
                $fileArr[] = $currentFile;
            }
        }
        return $fileArr;
    }
}
<?php

namespace App\Console\Commands;


use App\Models\Invoice;
use Illuminate\Console\Command;

class ImportImg extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:importImg';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '将嘉善通的发票图片批量下载到本地';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //获取参数
        $arr = Invoice::orderBy('id', 'asc')->get();
        $cnt = 0;
        foreach($arr as $value){
            $accountSetId = $value['account_set_id'];
            $period = $value['period'];
            $url = $value['url'];
            $dir = $this->getFolder($accountSetId, $period);
            if($dir){
                $this->download($url, $dir);
            }
            $cnt++;
        }

        if ($cnt%1000 == 0 ) {
            echo '已完成'.$cnt.'个';
            sleep(20);
        }
        echo '成功下载'.$cnt;
    }

    function getFolder($accountSetId, $period) {
        if (strlen($accountSetId) >= 5 * 3) {
            $levelPathArr = array_slice(str_split($accountSetId, 3), 0, 5);
            $levelPath = implode('/', $levelPathArr) . '/' . $accountSetId;
        }

        // 0 增值税发票，1 银行回单，2 费用票
        $folder_name = 'invoice';
        $cfgPath = 'D:/images';
        $levelPath = $levelPath ?? $accountSetId;
        $dir = $cfgPath . '/' . $levelPath . '/' . $period . '/' . $folder_name;

        return $dir;
    }


    /**
     * @param $url
     * @param string $save_dir
     * @param string $filename
     * @return array
     */
    function download($url, $save_dir)
    {
        if(trim($save_dir)=='') {
            return array('file_name'=>'','save_path'=>'','error'=>1);
        }

        //创建保存目录
        if(!file_exists($save_dir)&&!mkdir($save_dir,0777,true)) {
            return array('file_name'=>'','save_path'=>'','error'=>5);
        }

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // 信任任何证书
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
        $file = curl_exec($ch);
        curl_close($ch);
        $filename = pathinfo($url, PATHINFO_BASENAME);
        $resource = fopen($save_dir .'/'. $filename, 'a');
        fwrite($resource, $file);
        fclose($resource);
        unset($file, $url);
        return array('file_name'=>$filename,'save_path'=>$save_dir.$filename,'error'=>0);
    }
}

<?php


namespace yunwuxin\model\helper;


use think\App;
use think\helper\Str;
use think\Model;

class BuildModel
{
    /** @var App  $app*/
    public $app;

    public $dir;

    /**
     * 批量生成 base模型和数据模型
     */
    public function build($app,$tables,$dir='model'){

        $this->app = $app;
        $this->dir = $dir;

        /** @var App  $app*/
        foreach ($tables as $table){

            $modelName =Str::studly($table);

            $modelBaseName = 'Base'.$modelName.'Model';

            $modelPath = $app->getBasePath().$dir.'/'.$modelName.'.php';

            $modelBasePath = $app->getBasePath().$dir.'/base/'.$modelBaseName.'.php';

            if (file_exists($modelPath)){
               continue;
            }

            $this->buildBase($modelBasePath,$modelBaseName,$table);
            $this->buildModel($modelPath,$modelName,$modelBaseName);

        }


        //查找数据库

        //模型目录

        //基础模型目录

        //每次执行基础模型

        //检测如果存在模型则不创建，不存在则创建


    }

    /**
     * 生成基础的文件
     */
    public function buildBase($path,$modelBaseName,$tableName){
        //创建base
        if (!is_dir($this->app->getBasePath().$this->dir.'/base')){
            mkdir($this->app->getBasePath().$this->dir.'/base', 0777);
        };

        if (!file_exists($this->app->getBasePath().$this->dir.'/base/CommonModel.php')){
            $commonModelTemplate = file_get_contents(__DIR__.'/stud/CommonModel');
            file_put_contents($this->app->getBasePath().$this->dir.'/base/CommonModel.php',$commonModelTemplate);
        }


        //生成基础
        $baseModelTemplate = file_get_contents(__DIR__.'/stud/BaseModel');

        $baseModelTemplate = str_replace('{{modelBaseName}}',$modelBaseName,$baseModelTemplate);
        $baseModelTemplate = str_replace('{{table}}',$tableName,$baseModelTemplate);

        file_put_contents($path,$baseModelTemplate);

    }


    /**
     * 生成模型
     */
    public function buildModel($path,$modelName,$modelBaseName){

        if (file_exists($path)){
            return true;
        }

        $template = file_get_contents(__DIR__.'/stud/Model');

        $template = str_replace('{{modelBaseName}}',$modelBaseName,$template);
        $template = str_replace('{{modelName}}',$modelName,$template);

        file_put_contents($path,$template);
    }

}
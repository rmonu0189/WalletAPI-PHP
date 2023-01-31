<?php

namespace MRPHPSDK\MRTerminal;

class MRTemplate{

    public static function controller($namespace, $class){
        $content = "<?php\nnamespace $namespace;";
        $content.="\n\nuse MRPHPSDK\\MRController\\MRController;\n";
        $content.="use MRPHPSDK\\MRRequest\\MRRequest;\n\n";
        $content.="class ".$class." extends MRController{\n\n";
        $content.="\tfunction __construct(){\n";
        $content.="\t\t";
        $content.="parent::__construct();\n";
        $content.="\t}";
        $content.="\n\n";
        $content.="\tpublic function index(MRRequest \$request){\n";
        $content.="\n";
        $content.="\t\treturn phpinfo();\n";
        $content.="\t}";
        $content.="\n\n}";
        return $content;
    }

    public static function model($namespace, $class){
        $content = "<?php\nnamespace $namespace;";
        $content.="\n\nuse MRPHPSDK\\MRModels\\MRModel;\n\n";
        $content.="class ".$class." extends MRModel{\n\n";
        $content.="\tfunction __construct(\$params = array()){\n";
        $content.="\t\t";
        $content.="parent::__construct(\$params);\n";
        $content.="\t}";
        $content.="\n\n}";
        return $content;
    }

    public static function middleware($namespace, $class){
        $content = "<?php\nnamespace $namespace;";
        $content.="\n\nuse MRPHPSDK\\MRMiddleware\\MRMiddleware;\n";
        $content.="use MRPHPSDK\\MRRequest\\MRRequest;\n\n";
        $content.="class ".$class." extends MRMiddleware{\n\n";

        //-- Adding comment
        $content.="\t/*\n";
        $content.="\t|--------------------------------------------------------------------------\n";
        $content.="\t| Handle all request\n";
        $content.="\t|--------------------------------------------------------------------------\n";
        $content.="\t*/\n";
        ///-- Create method
        $content.="\tpublic function handle(MRRequest \$request){\n";
        $content.="\n";
        $content.="\t\treturn \$this->success();";
        $content.="\n";
        $content.="\t}";
        $content.="\n\n}";
        return $content;
    }

    public static function route($package=""){
        $content = "<?php\n\n";
        $content.= "use \\MRPHPSDK\\MRRoute\\Route;\n\n";
        $content.="Route::group([\n";
        if($package!=""){
            $content.="\t'package' => '$package',\n";
            $content.="\t'prefix' => '$package',\n";
        }
        $content.="\t'middleware' => ['/Application".(($package!="")?"/Packages/$package":"")."/Middleware/AuthMiddleware'],\n], function(){\n";
        $content.="\tRoute::get('/Index');\n";
        $content.="});";
        return $content;
    }

    public static function migration($class){
        $content = "<?php";
        $content.="\n\nuse MRPHPSDK\\MRMigration\\DBSchema;\n";
        $content.="use MRPHPSDK\\MRMigration\\MRMigration;\n\n";
        $content.="class ".$class." extends MRMigration{\n\n";
        $content.="\tpublic function up(){\n";
        $content.="\n";
        $content.="\t}";
        $content.="\n\n}";
        return $content;
    }

}
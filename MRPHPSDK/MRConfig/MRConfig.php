<?php

namespace MRPHPSDK\MRConfig;

class MRConfig{

    private $config;

    /**
     * Call this method to get singleton
     *
     * @return Route
     */
    public static function instance()
    {
        static $inst = null;
        if ($inst === null) {
            $inst = new MRConfig();
            $inst->config = array();
            $inst->configureDefault();
        }
        return $inst;
    }

    public static function handle(){
        MRConfig::instance();
    }

    public static function set($conf){
        MRConfig::instance()->config = array_merge(MRConfig::instance()->config, $conf);
    }

    public static function get($key){
        return MRConfig::instance()->config[$key];
    }

    public static function setPackages(){
        if(file_exists(__DIR__.'/../../Application/package.json')){
            $homepage = file_get_contents(__DIR__.'/../../Application/package.json');
            $json = json_decode($homepage, true);
            MRConfig::set([
                'packages' => $json['package']
            ]);
        }
        else{
            MRConfig::set([
                'packages' => []
            ]);
        }
    }

    private function configureDefault(){
        MRConfig::set([
                'database' => [
                    'hostname'=>DATABASE_HOSTNAME,
                    'userName'=>DATABASE_USERNAME,
                    'password'=>DATABASE_PASSWORD,
                    'databaseName'=>DATABASE_NAME
                ]
            ]
        );
    }

}



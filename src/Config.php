<?php
namespace EvolutionCMS\EvocmsCommerceCoupons;


class Config
{
    private $config = [];

    public function __construct()
    {
        $this->load();
    }

    private function load()
    {
        $config = require __DIR__.'/../config.php';
        $customConfigPath = MODX_BASE_PATH.'core/custom/config/evocms-commerce-coupons.php';

        if(file_exists($customConfigPath)){
            $customRules = require $customConfigPath;

            $config = array_merge($config,$customRules);
        }

        $this->config = $config;
    }

    public function get($key,$default = null){
        if(!array_key_exists($key,$this->config) && strpos($key,'.') !==false){
            return $this->find($key,$this->config,$default);
        }

        if(!array_key_exists($key,$this->config) && $default === null){
            throw new \InvalidArgumentException("$key not found");
        }
        return $this->config[$key];
    }
    public function set($key,$value){
        $this->config[$key] = $value;
    }

    public function has($key,$value){
        return array_key_exists($key,$this->config);
    }


    public function setConfig($config){
        $this->config = $config;
    }

    private function find($key, array $config,$default)
    {

        $parts = explode('.',$key);



        if(array_key_exists($key,$config)){
            return $config[$key];
        }

        $first = array_shift($parts);


        if(count($parts)>0 && array_key_exists($first,$config)){
            return $this->find(implode('.',$parts),$config[$first],$default);
        }


        if(!array_key_exists($key,$this->config) && $default === null){
            throw new \InvalidArgumentException("$key not found");
        }
        return $default;

    }

}
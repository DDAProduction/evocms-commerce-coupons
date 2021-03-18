<?php
namespace EvolutionCMS\EvocmsCommerceCoupons;


class Assets
{
    private $css = [];
    private $js = [];

    public function addCss($file){
        $this->css[] = $file;
    }

    public function addJs($file){
        $this->js[] = $file;
    }


    public function getCss(){
        return $this->css;
    }

    public function getJs(){
        return $this->js;
    }
}
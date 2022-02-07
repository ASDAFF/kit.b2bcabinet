<?php
namespace Sotbit\B2bCabinet;

class Element {
    protected $id = 0;
    protected $name = '';
    protected $url = '';
    protected $img = '';

    public static function num2word($num = 1, $words = array()) {
        $num = $num % 100;
        if ($num > 19) {
            $num = $num % 10;
        }

        switch ($num){
            case 1:
                return($words[0]);
            case 2: case 3: case 4:
            return($words[1]);
            default:
                return($words[2]);
        }
    }

    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getName() {
        return $this->name;
    }

    public function setName($name) {
        $this->name = $name;
    }

    public function getUrl() {
        return $this->url;
    }

    public function setUrl($url) {
        $this->url = $url;
    }

    public function getImg() {
        return $this->img;
    }

    public function setImg($img) {
        $this->img = $img;
    }
}
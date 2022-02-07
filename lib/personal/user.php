<?php
namespace Kit\B2BCabinet\Personal;

use Bitrix\Main\UserTable;

class User extends \KitB2bCabinet
{
    protected $id = 0;
    protected $name = '';
    protected $lastName = '';
    protected $secondName = '';
    protected $personalPhone = '';
    protected $email = '';
    protected $personalPhoto = '';

    public function __construct($idUser = 0)
    {
        if($idUser > 0) {
            $user = UserTable::getList(['filter' => ['ID' => $idUser], 'limit' => 1, 'select' => ['ID', 'NAME', 'LAST_NAME', 'SECOND_NAME', 'PERSONAL_PHONE', 'EMAIL', 'PERSONAL_PHOTO']])->fetch();
            $this->id = $user['ID'];
            $this->name = $user['NAME'];
            $this->lastName = $user['LAST_NAME'];
            $this->secondName = $user['SECOND_NAME'];
            $this->personalPhone = $user['PERSONAL_PHONE'];
            $this->email = $user['EMAIL'];
            $this->personalPhoto = $user['PERSONAL_PHOTO'];
        }
    }

    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getLastName()
    {
        return $this->lastName;
    }

    public function getSecondName()
    {
        return $this->secondName;
    }

    public function getPersonalPhone()
    {
        return $this->personalPhone;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function getPersonalPhoto()
    {
        return $this->personalPhoto;
    }

    public function getFIO()
    {
        return trim($this->name.' '.$this->lastName.' '.$this->secondName);
    }

    public function genAvatar($settings = ['width' => 50, 'height' => 50, 'resize' => BX_RESIZE_IMAGE_EXACT])
    {
        if($this->personalPhoto > 0) {
            return \CFile::ResizeImageGet($this->personalPhoto, ['width' => $settings['width'], 'height' => $settings['height']], $settings['resize'], true);
        }
        else {
            return [];
        }
    }
}
<?php
/**
 * Created by PhpStorm.
 * User: x
 * Date: 19.02.2017
 * Time: 23:46
 */

namespace App\Libraries;


class Action
{
    const LOGIN = 20;
    const WRITE_A_POST = 15;
    const VOTE_POLL = 25;

    static function getConstants() {
        $oClass = new \ReflectionClass(__CLASS__);
        return $oClass->getConstants();
    }
}
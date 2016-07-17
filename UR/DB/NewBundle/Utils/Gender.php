<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace UR\DB\NewBundle\Utils;

/**
 * Description of Gender
 *
 * @author johanna
 */
class Gender extends BasicEnum {
    const __default = self::UNKNOWN;
    
    const UNKNOWN = 0;
    const MALE = 1;
    const FEMALE = 2;
}

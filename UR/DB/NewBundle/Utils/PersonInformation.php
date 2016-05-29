<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace UR\DB\NewBundle\Utils;

/**
 * Description of PersonInformation
 *
 * @author johanna
 */
class PersonInformation extends BasicEnum {
    const __default = self::BIRTH;
    
    const BIRTH = "birth";
    const BAPTISM = "baptism";
    const DEATH = "death";
    const EDUCATION = "education";
    const HONOUR = "honour";
    const PROPERTY = "property";
    const RANK = "rank";
    const RELIGION = "religion";
    const RESIDENCE = "residence";
    const ROAD_OF_LIFE = "roadOfLife";
    const STATUS = "status";
    const WORK = "work";
    const DATE = "date";
    const SOURCE = "source";
}

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
class PersonRelations extends BasicEnum {
    const __default = self::PARENT;
    
    const PARENT = "parent";
    const CHILD = "child";
    const GRANDPARENT = "grandparent";
    const GRANDCHILD = "grandchild";
    const SIBLING = "sibling";
    const MARRIAGE = "marriage";
    const PARENT_IN_LAW = "parentInLaw";
    const CHILD_IN_LAW = "childInLaw";
    const WEDDING = "wedding";
}

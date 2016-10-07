<?php

namespace UR\AmburgerBundle\Util;

use Doctrine\ORM\Query\Lexer;
use Doctrine\ORM\Query\Parser;
use Doctrine\ORM\Query\SqlWalker;
use Doctrine\ORM\Query\AST\Functions\FunctionNode;
 
//http://www.issart.com/blog/symfony-2-doctrine-2-get-random-entites-mysql/
class RandFunction extends FunctionNode
{
    public function parse(Parser $parser)
    {
        $parser->match(Lexer::T_IDENTIFIER);
        $parser->match(Lexer::T_OPEN_PARENTHESIS);
        $parser->match(Lexer::T_CLOSE_PARENTHESIS);
    }
 
    public function getSql(SqlWalker $sqlWalker)
    {
        return 'RAND()';
    }
}
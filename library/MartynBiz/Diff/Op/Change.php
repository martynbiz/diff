<?php namespace MartynBiz\Diff\Op;

use MartynBiz\Diff\Op;

/**
 * @package Text_Diff
 * @author  Geoffrey T. Dairiki <dairiki@dairiki.org>
 *
 * @access private
 */
class Change extends Op {

    function __construct($orig, $final)
    {
        $this->orig = $orig;
        $this->final = $final;
    }

    function &reverse()
    {
        $reverse = &new \MartynBiz\Diff\Op\Change($this->final, $this->orig);
        return $reverse;
    }

}
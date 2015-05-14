<?php namespace MartynBiz\Diff\Op;

use MartynBiz\Diff\Op;

/**
 * @package Text_Diff
 * @author  Geoffrey T. Dairiki <dairiki@dairiki.org>
 *
 * @access private
 */
class Add extends Op {

    function __construct($lines)
    {
        $this->final = $lines;
        $this->orig = false;
    }

    function &reverse()
    {
        $reverse = &new \MartynBiz\Diff\Op\Delete($this->final);
        return $reverse;
    }

}
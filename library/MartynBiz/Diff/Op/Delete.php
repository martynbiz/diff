<?php namespace MartynBiz\Diff\Op;

use MartynBiz\Diff\Op;

/**
 * @package Text_Diff
 * @author  Geoffrey T. Dairiki <dairiki@dairiki.org>
 *
 * @access private
 */
class Delete extends Op {

    function __construct($lines)
    {
        $this->orig = $lines;
        $this->final = false;
    }

    function &reverse()
    {
        $reverse = &new \MartynBiz\Diff\Op\Add($this->orig);
        return $reverse;
    }

}
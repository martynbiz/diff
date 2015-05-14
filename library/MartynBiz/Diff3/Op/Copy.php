<?php namespace MartynBiz\Diff\Diff3\Op;

use MartynBiz\Diff\Diff3\Op;

/**
 * @package Text_Diff
 * @author  Geoffrey T. Dairiki <dairiki@dairiki.org>
 *
 * @access private
 */
class Copy extends Op {

    function __construct($lines = false)
    {
        $this->orig = $lines ? $lines : array();
        $this->final1 = &$this->orig;
        $this->final2 = &$this->orig;
    }

    function merged()
    {
        return $this->orig;
    }

    function isConflict()
    {
        return false;
    }

}
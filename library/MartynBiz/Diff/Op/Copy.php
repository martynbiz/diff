<?php namespace MartynBiz\Diff\Op;

use MartynBiz\Diff\Op;

/**
 * @package Text_Diff
 * @author  Geoffrey T. Dairiki <dairiki@dairiki.org>
 *
 * @access private
 */
class Copy extends Op {

    function __construct($orig, $final = false)
    {
        if (!is_array($final)) {
            $final = $orig;
        }
        $this->orig = $orig;
        $this->final = $final;
    }

    function &reverse()
    {
        $reverse = &new \MartynBiz\Diff\Op\Copy($this->final, $this->orig);
        return $reverse;
    }

}
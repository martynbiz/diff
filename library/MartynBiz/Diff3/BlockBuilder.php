<?php namespace MartynBiz\Diff\Diff3;

/**
 * @package Text_Diff
 * @author  Geoffrey T. Dairiki <dairiki@dairiki.org>
 *
 * @access private
 */
class BlockBuilder {

    function __construct()
    {
        $this->_init();
    }

    function input($lines)
    {
        if ($lines) {
            $this->_append($this->orig, $lines);
        }
    }

    function out1($lines)
    {
        if ($lines) {
            $this->_append($this->final1, $lines);
        }
    }

    function out2($lines)
    {
        if ($lines) {
            $this->_append($this->final2, $lines);
        }
    }

    function isEmpty()
    {
        return !$this->orig && !$this->final1 && !$this->final2;
    }

    function finish()
    {
        if ($this->isEmpty()) {
            return false;
        } else {
            $edit = new \MartynBiz\Diff\Diff3\Op\Op($this->orig, $this->final1, $this->final2);
            $this->_init();
            return $edit;
        }
    }

    function _init()
    {
        $this->orig = $this->final1 = $this->final2 = array();
    }

    function _append(&$array, $lines)
    {
        array_splice($array, sizeof($array), 0, $lines);
    }

}
<?php namespace MartynBiz\Diff;
/**
 * A class for computing three way diffs.
 *
 * $Horde: framework/Text_Diff/Diff3.php,v 1.2.10.7 2009/01/06 15:23:41 jan Exp $
 *
 * Copyright 2007-2009 The Horde Project (http://www.horde.org/)
 *
 * See the enclosed file COPYING for license information (LGPL). If you did
 * not receive this file, see http://opensource.org/licenses/lgpl-license.php.
 *
 * @package Text_Diff
 * @since   0.3.0
 */

/** Text_Diff */
// require_once 'Text/Diff.php';

/**
 * A class for computing three way diffs.
 *
 * @package Text_Diff
 * @author  Geoffrey T. Dairiki <dairiki@dairiki.org>
 */
class Diff3 extends Diff {

    /**
     * Conflict counter.
     *
     * @var integer
     */
    var $_conflictingBlocks = 0;

    /**
     * Computes diff between 3 sequences of strings.
     *
     * @param array $orig    The original lines to use.
     * @param array $final1  The first version to compare to.
     * @param array $final2  The second version to compare to.
     */
    function __construct($orig, $final1, $final2)
    {
        if (extension_loaded('xdiff')) {
            $engine = new \MartynBiz\Diff\Engine\Xdiff();
        } else {
            $engine = new \MartynBiz\Diff\Engine\Native();
        }

        $this->_edits = $this->_diff3($engine->diff($orig, $final1),
                                      $engine->diff($orig, $final2));
    }

    /**
     */
    function mergedOutput($label1 = false, $label2 = false)
    {
        $lines = array();
        foreach ($this->_edits as $edit) {
            if ($edit->isConflict()) {
                /* FIXME: this should probably be moved somewhere else. */
                $lines = array_merge($lines,
                                     array('<<<<<<<' . ($label1 ? ' ' . $label1 : '')),
                                     $edit->final1,
                                     array("======="),
                                     $edit->final2,
                                     array('>>>>>>>' . ($label2 ? ' ' . $label2 : '')));
                $this->_conflictingBlocks++;
            } else {
                $lines = array_merge($lines, $edit->merged());
            }
        }

        return $lines;
    }

    /**
     * @access private
     */
    function _diff3($edits1, $edits2)
    {
        $edits = array();
        $bb = new \MartynBiz\Diff3\BlockBuilder();

        $e1 = current($edits1);
        $e2 = current($edits2);
        while ($e1 || $e2) {
            if ($e1 && $e2 && is_a($e1, 'MartynBiz\Diff\Op\Copy') && is_a($e2, 'MartynBiz\Diff\Op\Copy')) {
                /* We have copy blocks from both diffs. This is the (only)
                 * time we want to emit a diff3 copy block.  Flush current
                 * diff3 diff block, if any. */
                if ($edit = $bb->finish()) {
                    $edits[] = $edit;
                }

                $ncopy = min($e1->norig(), $e2->norig());
                assert($ncopy > 0);
                $edits[] = new \MartynBiz\Diff3\Op\Copy(array_slice($e1->orig, 0, $ncopy));

                if ($e1->norig() > $ncopy) {
                    array_splice($e1->orig, 0, $ncopy);
                    array_splice($e1->final, 0, $ncopy);
                } else {
                    $e1 = next($edits1);
                }

                if ($e2->norig() > $ncopy) {
                    array_splice($e2->orig, 0, $ncopy);
                    array_splice($e2->final, 0, $ncopy);
                } else {
                    $e2 = next($edits2);
                }
            } else {
                if ($e1 && $e2) {
                    if ($e1->orig && $e2->orig) {
                        $norig = min($e1->norig(), $e2->norig());
                        $orig = array_splice($e1->orig, 0, $norig);
                        array_splice($e2->orig, 0, $norig);
                        $bb->input($orig);
                    }

                    if (is_a($e1, 'MartynBiz\Diff\Op\Copy')) {
                        $bb->out1(array_splice($e1->final, 0, $norig));
                    }

                    if (is_a($e2, 'MartynBiz\Diff\Op\Copy')) {
                        $bb->out2(array_splice($e2->final, 0, $norig));
                    }
                }

                if ($e1 && ! $e1->orig) {
                    $bb->out1($e1->final);
                    $e1 = next($edits1);
                }
                if ($e2 && ! $e2->orig) {
                    $bb->out2($e2->final);
                    $e2 = next($edits2);
                }
            }
        }

        if ($edit = $bb->finish()) {
            $edits[] = $edit;
        }

        return $edits;
    }

}
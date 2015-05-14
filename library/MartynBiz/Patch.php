<?php namespace MartynBiz\Diff;

class Patch
{
    /**
     * Applies a diff to a string -- untested yo
     * 
     * @param string $diff Diff
     * @param string $text String to patch
     * 
     * @return string Patched string
     * */
    static function apply($diff, $text)
    {
        $tmpname = tempnam(sys_get_temp_dir(), "diff");
        $outname = tempnam(sys_get_temp_dir(), "diff");
        $tmp = fopen($tmpname, "w");
        $out = fopen($outname, "r");
        fwrite($tmp, $text.PHP_EOL);
        $proc = proc_open(
            'patch '.$tmpname.' -o '.$outname, array(
               0 => array("pipe", "r"),
               1 => array("pipe", "w"),
               2 => array("pipe", "w")
            ), $pipes
        );
        if (is_resource($proc)) {
            fwrite($pipes[0], $diff);
            fclose($pipes[0]);
            stream_get_contents($pipes[1]);
            $newText = stream_get_contents($out);
            fclose($pipes[1]);
            return $newText;
        }
    }
}
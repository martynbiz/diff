#Diff#

##Introduction##

Engine for performing and rendering text diffs. Basically the old PEAR library
but I added 5.3+ namespacing and intend to include a patch method.

##Installation##

```
composer require martynbiz/diff
```

##Usage##

```
// code to highlight the difference between strings
$a_lines = explode("\n", $a);
$b_lines = explode("\n", $b);

$check_diff = new \MartynBiz\Diff\Diff( 'auto', array($a_lines, $b_lines) );

// set which render to use
// $renderer = new \MartynBiz\Diff\Renderer\Unified();
$renderer = new \MartynBiz\Diff\Renderer\Inline();

$diff = $renderer->render($check_diff);
```

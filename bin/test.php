<?php
function foo($x) {
    return bar($x * 2);
}
function bar($y) {
    return $y + 3;
}
foo(10);
echo "Trace should be written to /var/log/xdebug\n";
?>

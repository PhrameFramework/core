<h1><?php echo $type; ?></h1>

<code>
Code: <?php echo $code; ?><br />
Message: <?php echo $message; ?><br />
in <?php echo $file, ' [', $line, ']' ?>
</code>

<h3>Trace:</h3>

<code>
<?php
foreach ($trace as $t)
{
    $file = isset($t['file']) ? $t['file'] : '';
    $line = isset($t['line']) ? $t['line'] : '';
    $class = isset($t['class']) ? $t['class'] : '';
    $type = isset($t['type']) ? $t['type'] : '';
    $function = isset($t['function']) ? $t['function'] : '';
    ?>

    <?php echo $file; ?> [<?php echo $line; ?>] <?php echo $class.$type.$function; ?><br />

    <?php
}
?>
</code>

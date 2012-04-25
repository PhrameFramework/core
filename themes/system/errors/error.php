<?php
/**
 * @var  int     $code
 * @var  string  $message
 * @var  string  $file
 * @var  int     $line
 */
?>

<h1>Error</h1>

<code>
Code: <?php echo $code; ?><br />
Message: <?php echo $message; ?><br />
in <?php echo $file, ' [', $line, ']' ?>
</code>

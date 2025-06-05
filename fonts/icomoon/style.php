<?php
header('Content-type: text/css');

// Read the CSS file
$css = file_get_contents(__DIR__ . '/style.css');

// Replace the font paths
$css = str_replace('<?php echo APPURL; ?>fonts/icomoon/fonts/', 'fonts/', $css);

// Output the modified CSS
echo $css; 
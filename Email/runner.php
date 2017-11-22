<?php

include('PlancakeEmailParser.php');

$contents = file_get_contents('./Example/RE Example.eml');

//echo $contents;

$p = new PlancakeEmailParser($contents);
echo $p->getSubject() . "\n";
echo "To: " . implode($p->getTo(), ", ") . "\n";
echo $p->getHeader("From") . "\n";
echo $p->getHeader("Sent") . "\n";
//echo $p->getBody() . "\n";


<?php

declare(strict_types=1);
$document = new \DOMDocument('1.0', 'UTF-8');
$document->formatOutput = true;
$action = $document->createElement('ListObjectsV2');
$document->appendChild($action);

echo $document->saveXML();

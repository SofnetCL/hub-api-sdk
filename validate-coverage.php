<?php
if ($argc < 2) {
    echo "Usage: php validate-coverage.php <min_coverage_percentage>\n";
    exit(1);
}

$minCoverage = (float) $argv[1];

$xml = new SimpleXMLElement(file_get_contents('coverage/clover.xml'));
$metrics = $xml->xpath('//metrics')[0];

$covered = (int)$metrics['coveredstatements'];
$total = (int)$metrics['statements'];

$coverage = ($total > 0) ? ($covered / $total) * 100 : 0;

echo "Coverage: " . round($coverage, 2) . "%\n";

if ($coverage < $minCoverage) {
    echo "Error: Coverage is below $minCoverage%.\n";
    exit(1); // Retorna un error si la cobertura es inferior al porcentaje indicado
}

echo "Success: Coverage is $minCoverage% or higher.\n";
exit(0);

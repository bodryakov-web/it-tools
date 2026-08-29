<?php
$testCases = [
    'test-slug',
    'test',
    'test-',
    '-test',
    'test--slug',
    'test_slug',
    'Test-Slug',
    'test slug'
];

echo "<pre>";
foreach ($testCases as $test) {
    $result = preg_match('/^[a-z-]+$/', $test);
    echo "$test: " . ($result ? 'PASS' : 'FAIL') . "\n";
}
echo "</pre>";

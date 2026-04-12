<?php
// Patch all migration files to add hasTable guards
$files = glob('database/migrations/2026_03_28_2124*.php');
foreach ($files as $file) {
    $content = file_get_contents($file);
    if (strpos($content, 'Schema::hasTable') !== false) {
        echo "Already patched: $file\n";
        continue;
    }
    // Match table name and wrap
    preg_match("/Schema::create\('([^']+)'/", $content, $m);
    if (!isset($m[1])) { echo "No match: $file\n"; continue; }
    $table = $m[1];
    $content = str_replace(
        "Schema::create('$table',",
        "if (Schema::hasTable('$table')) return;\n        Schema::create('$table',",
        $content
    );
    file_put_contents($file, $content);
    echo "Patched: $file ($table)\n";
}
echo "Done.\n";

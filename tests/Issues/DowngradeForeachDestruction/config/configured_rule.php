<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\DowngradePhp55\Rector\Foreach_\DowngradeForeachListRector;
use Rector\DowngradePhp71\Rector\Array_\SymmetricArrayDestructuringToListRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->import(__DIR__ . '/../../../../config/config.php');
    $rectorConfig->rule(SymmetricArrayDestructuringToListRector::class);
    $rectorConfig->rule(DowngradeForeachListRector::class);
};

<?php

namespace Rector\Tests\DowngradePhp71\Rector\Array_\SymmetricArrayDestructuringToListRector\Fixture;
final class IncludeEmpty
{
    public function __construct(array $data)
    {
        [, $id1, $name1] = $data;
    }
}

?>
-----
<?php

namespace Rector\Tests\DowngradePhp71\Rector\Array_\SymmetricArrayDestructuringToListRector\Fixture;
final class IncludeEmpty
{
    public function __construct(array $data)
    {
        list(, $id1, $name1) = $data;
    }
}

?>

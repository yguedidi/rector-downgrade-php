<?php

declare(strict_types=1);

namespace Rector\DowngradePhp72\NodeAnalyzer;

use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Stmt\If_;
use Rector\Core\PhpParser\Node\BetterNodeFinder;
use Rector\Core\PhpParser\Node\Value\ValueResolver;
use Rector\NodeNameResolver\NodeNameResolver;

final class FunctionExistsFunCallAnalyzer
{
    public function __construct(
        private readonly BetterNodeFinder $betterNodeFinder,
        private readonly NodeNameResolver $nodeNameResolver,
        private readonly ValueResolver $valueResolver
    ) {
    }

    public function detect(FuncCall $funcCall, string $functionName): bool
    {
        /** @var If_|null $firstParentIf */
        $firstParentIf = $this->betterNodeFinder->findParentType($funcCall, If_::class);
        if (! $firstParentIf instanceof If_) {
            return false;
        }

        if (! $firstParentIf->cond instanceof FuncCall) {
            return false;
        }

        if (! $this->nodeNameResolver->isName($firstParentIf->cond, 'function_exists')) {
            return false;
        }

        /** @var FuncCall $functionExists */
        $functionExists = $firstParentIf->cond;

        if (! isset($functionExists->getArgs()[0])) {
            return false;
        }

        return $this->valueResolver->isValue($functionExists->getArgs()[0]->value, $functionName);
    }
}

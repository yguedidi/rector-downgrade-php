<?php

declare(strict_types=1);

namespace Rector\DowngradePhp54\Rector\FunctionLike;

use PhpParser\Node;
use PhpParser\Node\Expr\Closure;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Function_;
use PHPStan\Type\CallableType;
use Rector\Core\Rector\AbstractRector;
use Rector\PhpDocDecorator\PhpDocFromTypeDeclarationDecorator;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://wiki.php.net/rfc/callable
 *
 * @see \Rector\Tests\DowngradePhp54\Rector\FunctionLike\DowngradeCallableTypeDeclarationRector\DowngradeCallableTypeDeclarationRectorTest
 */
final class DowngradeCallableTypeDeclarationRector extends AbstractRector
{
    public function __construct(
        private readonly PhpDocFromTypeDeclarationDecorator $phpDocFromTypeDeclarationDecorator
    ) {
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [Function_::class, ClassMethod::class, Closure::class];
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Remove the "callable" param type, add a @param tag instead',
            [
                new CodeSample(
                    <<<'CODE_SAMPLE'
class SomeClass
{
    public function someFunction(callable $callback)
    {
    }
}
CODE_SAMPLE
                    ,
                    <<<'CODE_SAMPLE'
class SomeClass
{
    /**
     * @param callable $callback
     */
    public function someFunction($callback)
    {
    }
}
CODE_SAMPLE
                ),
            ]
        );
    }

    /**
     * @param ClassMethod|Closure|Function_ $node
     */
    public function refactor(Node $node): ?Node
    {
        $callableType = new CallableType();

        $hasChanged = false;
        $hasParamChanged = false;

        foreach ($node->getParams() as $param) {
            $hasParamChanged = $this->phpDocFromTypeDeclarationDecorator->decorateParamWithSpecificType(
                $param,
                $node,
                $callableType
            );
            if ($hasParamChanged) {
                $hasChanged = true;
            }
        }

        if ($hasChanged) {
            return $node;
        }

        return null;
    }
}

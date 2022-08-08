<?php

namespace App\Doctrine\Common\Collections;

use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\Selectable;
use JetBrains\PhpStorm\Pure;

class ReadOnlyCollectionException extends \LogicException
{
    #[Pure]
    public function __construct(string $action)
    {
        parent::__construct(sprintf('Could not %s read-only collection, the collection is read-only.', $action));
    }

    #[Pure]
    public static function notSelectable(Collection $collection): self
    {
        return new self(sprintf(
            'Collection %s does not implement %s, so you cannot call ->matching() over it.',
            Selectable::class,
            get_class($collection)
        ));
    }
}

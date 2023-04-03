<?php
declare(strict_types=1);
namespace In2code\Osm\Tca;

use TYPO3\CMS\Core\Utility\MathUtility;

class IsContentStoredDisplayCondition
{
    public function match(array $information): bool
    {
        return $this->isPersisted($information);
    }

    protected function isPersisted(array $information): bool
    {
        return !empty($information['record']['uid'])
            && MathUtility::canBeInterpretedAsInteger($information['record']['uid']);
    }
}

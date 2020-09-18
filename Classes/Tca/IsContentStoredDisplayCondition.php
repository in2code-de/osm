<?php
declare(strict_types=1);
namespace In2code\Osm\Tca;

use TYPO3\CMS\Core\Utility\MathUtility;

/**
 * Class IsContentStoredDisplayCondition
 */
class IsContentStoredDisplayCondition
{
    /**
     * @param array $information
     * @return bool
     */
    public function match(array $information): bool
    {
        return $this->isPersisted($information);
    }

    /**
     * @param array $information
     * @return bool
     */
    protected function isPersisted(array $information): bool
    {
        return !empty($information['record']['uid'])
            && MathUtility::canBeInterpretedAsInteger($information['record']['uid']);
    }
}

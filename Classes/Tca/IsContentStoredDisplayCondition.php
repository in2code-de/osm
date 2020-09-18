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
        $result = $this->isPersisted($information);
        if (!empty($information['conditionParamters'][0]) && $information['conditionParamters'][0] === 'negate') {
            $result = !$result;
        }
        return $result;
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

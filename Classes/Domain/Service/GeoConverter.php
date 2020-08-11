<?php
declare(strict_types=1);
namespace In2code\Osm\Domain\Service;

use In2code\Osm\Exception\RequestFailedException;
use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Core\Http\RequestFactory;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Class GeoConverter
 */
class GeoConverter
{
    /**
     * @param string $address e.g. "Geschwister-Scholl-Platz, 72074 Tübingen, Deutschland"
     * @return array e.g. [48.5294782, 9.0415853]
     * @throws RequestFailedException
     */
    public function convertAddressToCoordinates(string $address): array
    {
        $url = 'https://nominatim.openstreetmap.org/search?format=json&polygon=1&addressdetails=1&q=';
        $url .= urlencode($address);
        $results = $this->getJsonResultFromUrl($url);
        if (!empty($results[0]['lat']) && !empty($results[0]['lon'])) {
            return [(float)$results[0]['lat'], (float)$results[0]['lon']];
        }
        return [];
    }

    /**
     * @param string $url
     * @return array
     * @throws RequestFailedException
     */
    protected function getJsonResultFromUrl(string $url): array
    {
        $requestFactory = GeneralUtility::makeInstance(RequestFactory::class);
        /** @var ResponseInterface $response */
        $response = $requestFactory->request($url, 'GET');
        if ($response->getStatusCode() === 200) {
            if (strpos($response->getHeaderLine('Content-Type'), 'application/json') === 0) {
                $json = $response->getBody()->getContents();
                $result = json_decode($json, true);
                if (is_array($result)) {
                    return $result;
                }
            } else {
                throw new RequestFailedException('No json returned', 1597140831);
            }
        } else {
            throw new RequestFailedException('Could not connect to given address', 1597140820);
        }
        return [];
    }
}

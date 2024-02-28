<?php
declare(strict_types=1);

namespace Bonecki\PokeAPI\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\HTTP\Client\Curl;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Bonecki\PokeAPI\Model\Cache\PokemonCache;
use Magento\Framework\Message\ManagerInterface;
use Magento\Framework\Serialize\SerializerInterface;

class PokeAPI extends AbstractHelper
{
    private const POKEAPI_URL_PATH = 'pokeapi/options/pokeapi_url';

    /**
     * @var Curl
     */
    private readonly Curl $curl;

    /**
     * @var PokemonCache
     */
    private readonly PokemonCache $pokemonCache;

    /**
     * @var SerializerInterface
     */
    private readonly SerializerInterface $serializer;
    /**
     * @var ManagerInterface
     */
    protected readonly ManagerInterface $messageManager;

    /**
     * @param Curl $curl
     * @param ScopeConfigInterface $scopeConfig
     * @param PokemonCache $pokemonCache
     * @param SerializerInterface $serializer
     * @param ManagerInterface $messageManager
     */
    public function __construct(
        Curl                 $curl,
        ScopeConfigInterface $scopeConfig,
        PokemonCache         $pokemonCache,
        SerializerInterface  $serializer,
        ManagerInterface $messageManager
    )
    {
        $this->curl = $curl;
        $this->pokemonCache = $pokemonCache;
        $this->scopeConfig = $scopeConfig;
        $this->serializer = $serializer;
        $this->messageManager = $messageManager;

    }

    /**
     * @param string $name
     * @return array
     */
    public function requestPokemon(string $name): array
    {
        $cachedPokemon = $this->getCachedResponse($name);
        if(!empty($cachedPokemon))
        {
            return $cachedPokemon;
        }

        try {
            $this->curl->get($this->getPokeApiUrl() . $name);
            $response = $this->curl->getBody();
            if (strcmp($response, "Not Found") !== 0) {
                $this->saveResponseCache($response, $name);
                return $this->serializer->unserialize($response);
            }
            return [];
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage(__("PokeAPI request failed"));
        }

        return [];
    }

    /**
     * @return string
     */
    private function getPokeApiUrl(): string
    {
        return $this->scopeConfig->getValue(self::POKEAPI_URL_PATH);
    }

    /**
     * @param string $response
     * @param string $cacheIdentifier
     * @return void
     */
    private function saveResponseCache(string $response, string $cacheIdentifier): void
    {
        $this->pokemonCache->save($response, $cacheIdentifier, [PokemonCache::CACHE_TAG],86400);
    }

    /**
     * @param string $name
     * @return array
     */
    private function getCachedResponse(string $name): array
    {
        $cache = $this->pokemonCache->load($name);
        if ($cache) {
            return $this->serializer->unserialize($cache);
        }
        return [];
    }
}

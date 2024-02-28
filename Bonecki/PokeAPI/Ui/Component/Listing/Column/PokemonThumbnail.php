<?php

namespace Bonecki\PokeAPI\Ui\Component\Listing\Column;

use Bonecki\PokeAPI\Helper\PokeAPI;
use Magento\Framework\Message\ManagerInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Ui\Component\Listing\Columns\Column;

class PokemonThumbnail extends Column
{
    const POKEMON_THUMB_FIELD = 'poke_thumb';

    /**
     * @var PokeAPI
     */
    private $pokeAPI;

    /**
     * @var ManagerInterface
     */
    protected $messageManager;

    /**
     * @param ContextInterface $context
     * @param UiComponentFactory $uiComponentFactory
     * @param array $components
     * @param array $data
     */
    public function __construct(
        ContextInterface   $context,
        UiComponentFactory $uiComponentFactory,
        PokeAPI            $pokeAPI,
        ManagerInterface   $messageManager,
        array              $components = [],
        array              $data = []
    )
    {
        $this->pokeAPI = $pokeAPI;
        $this->messageManager = $messageManager;
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

    /**
     * Prepare Data Source
     *
     * @param array $dataSource
     * @return array
     */
    public function prepareDataSource(array $dataSource): array
    {
        $unknownPokemons = null;
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as & $item) {
                $result = $this->pokeAPI->requestPokemon($item['pokemon_name']);
                if (!empty($result)) {
                    $url = $result['sprites']['front_default'];
                    $item['name'] = $result['name'];
                    $item[self::POKEMON_THUMB_FIELD . '_src'] = $url;
                    $item[self::POKEMON_THUMB_FIELD . '_alt'] = $item['pokemon_name'];
                    $item[self::POKEMON_THUMB_FIELD . '_orig_src'] = $url;
                } else {
                    $unknownPokemons[] = $item['pokemon_name'];
                }
            }
        }
        if ($unknownPokemons !== null) {
            $this->messageManager->addErrorMessage(
                __("Unknown pokemon: " . implode(', ', array_unique($unknownPokemons)))
            );
        }
        return $dataSource;
    }

}

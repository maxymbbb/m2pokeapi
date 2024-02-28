# Mage2 Module Bonecki PokeAPI

    ``bonecki/module-pokeapi``

 - [Main Functionalities](#markdown-header-main-functionalities)
 - [Installation](#markdown-header-installation)
 - [Configuration](#markdown-header-configuration)
 - [Specifications](#markdown-header-specifications)
 - [Attributes](#markdown-header-attributes)


## Main Functionalities
Display Pokemon name and image in product details and grid

## Installation

### Type 1: Zip file
 - Unzip the zip file in `app/code/Bonecki`
 - Enable the module by running `php bin/magento module:enable Bonecki_PokeAPI`
 - Apply database updates by running `php bin/magento setup:upgrade`\*
 - Flush the cache by running `php bin/magento cache:flush`

### Type 2: Composer


 - Add the composer repository by adding to composer.json 
`    "repositories": [
   {
   "type": "path",
   "url": "../../packages/module-pokeapi"
   }
   ],`
 - Extract module files to `{project_root_dir}/packages/module-pokeapi`
 - Install the module composer by running `composer require bonecki/module-pokeapi`
 - enable the module by running `php bin/magento module:enable Bonecki_PokeAPI`
 - apply database updates by running `php bin/magento setup:upgrade`\*
 - Flush the cache by running `php bin/magento cache:flush`


## Configuration

 - PokeAPI URL (configuration/options/pokeapi_url)


## Specifications

 - Cache
	- PokemonCache - pokemoncache_cache_tag > Bonecki\PokeAPI\Model\Cache\PokemonCache


## Attributes

 - Product - Pokemon Name (pokemon_name)


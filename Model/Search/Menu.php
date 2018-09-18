<?php

namespace Metagento\BackendSearch\Model\Search;

/**
 *
 * @method Customer setQuery(string $query)
 * @method string|null getQuery()
 * @method bool hasQuery()
 * @method Customer setStart(int $startPosition)
 * @method int|null getStart()
 * @method bool hasStart()
 * @method Customer setLimit(int $limit)
 * @method int|null getLimit()
 * @method bool hasLimit()
 * @method Customer setResults(array $results)
 * @method array getResults()
 * @api
 * @since 100.0.2
 */

class Menu
    extends \Magento\Framework\DataObject
{
    public function __construct(
        \Magento\Backend\Model\UrlInterface $url,
        \Magento\Backend\Model\Menu\Config $menuConfig
    ) {
        $this->url = $url;
        $this->menuConfig = $menuConfig;
    }


    public function load()
    {
        $result = [];
        if (!$this->hasStart() || !$this->hasLimit() || !$this->hasQuery()) {
            $this->setResults($result);
            return $this;
        }
        $data = array();
        $query = $this->getQuery();

        $configMenu = $this->menuConfig->getMenu()->toArray();
        foreach ($configMenu as $id => $menu) {
            if ($menu['action']) {
                $data[] = array(
                    "id" => "menu/$id",
                    "type" => "Menu",
                    "name" => $menu['title'],
                    "description" => $menu['title'],
                    "url" => $this->url->getUrl($menu['action'])
                );
            }
            if (is_array($menu['sub_menu'])) {
                foreach ($menu['sub_menu'] as $secondId => $secondMenu) {
                    if ($secondMenu['action']) {
                        $data[] = array(
                            "id" => "menu/$id/$secondId",
                            "type" => "Menu",
                            "name" => $secondMenu['title'],
                            "description" => $menu['title'] . ' -> ' . $secondMenu['title'],
                            "url" => $this->url->getUrl($secondMenu['action'])
                        );
                    }
                    if (is_array($secondMenu['sub_menu'])) {
                        foreach ($secondMenu['sub_menu'] as $thirdId => $thirdMenu) {
                            if ($thirdMenu['action']) {
                                $data[] = array(
                                    "id" => "menu/$id/$secondId/$thirdId",
                                    "type" => "Menu",
                                    "name" => $thirdMenu['title'],
                                    "description" => $menu['title'] . ' -> ' . $secondMenu['title'] . ' -> ' . $thirdMenu['title'],
                                    "url" => $this->url->getUrl($thirdMenu['action'])
                                );
                            }
                        }
                    }
                }
            }
        }

        foreach ($data as $index => $item) {
            if (strpos(strtolower($item['name']), strtolower($query)) === false) {
                unset($data[$index]);
            }
        }

        $this->setResults($data);

        return $this;
    }
}
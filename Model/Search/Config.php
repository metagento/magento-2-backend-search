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

class Config
    extends \Magento\Framework\DataObject
{
    public function __construct(
        \Magento\Config\Model\Config\Structure $configStructure,
        \Magento\Backend\Model\UrlInterface $url
    ) {
        $this->url = $url;
        $this->configStructure = $configStructure;
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
        $tabs = $this->configStructure->getTabs();
        foreach ($tabs as $tab) {
            $tabLabel = $tab->getLabel();
            foreach ($tab->getChildren() as $section) {
                $sectionId = $section->getId();
                $sectionLabel = $section->getLabel();
                $data[] = array(
                    "id" => "config/$sectionId",
                    "type" => "Configuration",
                    "name" => $sectionLabel,
                    "description" => "$tabLabel -> $sectionLabel",
                    "url" => $this->url->getUrl("*/system_config/edit/section/$sectionId")
                );
                foreach ($section->getChildren() as $group) {
                    $groupId = $group->getId();
                    $groupLabel = $group->getLabel();
                    $data[] = array(
                        "id" => "config/$sectionId/$groupId",
                        "type" => "Configuration",
                        "name" => $groupLabel,
                        "description" => "$tabLabel -> $sectionLabel -> $groupLabel",
                        "url" => $this->url->getUrl("*/system_config/edit/section/$sectionId") . "#$sectionId" . "_" . "$groupId" . "-link"
                    );
                    foreach ($group->getChildren() as $field) {
                        $fieldId = $field->getId();
                        $fieldLabel = $field->getLabel();
                        $data[] = array(
                            "id" => "config/$sectionId/$groupId/$fieldId",
                            "type" => "Configuration",
                            "name" => $fieldLabel,
                            "description" => "$tabLabel -> $sectionLabel -> $groupLabel -> $fieldLabel",
                            "url" => $this->url->getUrl("*/system_config/edit/section/$sectionId") . "#$sectionId" . "_" . "$groupId" . "-link"
                        );
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
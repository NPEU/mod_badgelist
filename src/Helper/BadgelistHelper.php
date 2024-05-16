<?php

namespace NPEU\Module\Badgelist\Site\Helper;

use Joomla\CMS\Application\SiteApplication;
use Joomla\CMS\Factory;
use Joomla\Database\DatabaseAwareInterface;
use Joomla\Database\DatabaseAwareTrait;
use Joomla\Registry\Registry;

defined('_JEXEC') or die;

/**
 * Helper for mod_badgelist
 *
 * @since  1.5
 */
class BadgelistHelper implements DatabaseAwareInterface
{
    use DatabaseAwareTrait;

    public function getBadges(Registry $config, SiteApplication $app): array
    {
        if (!$app instanceof SiteApplication) {
            return [];
        }

        $badges_data = $config->get('badges');
        $badges = [];
        foreach ($badges_data as $badge_data) {
            if (empty($badge_data->brand_id)) {
                continue;
            }
            $badges[] = $this->getBrand($badge_data);
        }

        return $badges;
    }

    public function getBrand($data): object
    {
        $db = $this->getDatabase();

        $query = $db->getQuery(true);
        $query->select('*');
        $query->from('#__brands');
        $query->where('id = ' . $data->brand_id);
        $db->setQuery($query);
        $brand = $db->loadObject();

        if (empty($brand->params)) {
            $brand->params = '{}';
        }


        // Process params:
        $brand->params = json_decode($brand->params);
        // Where $params is a JSON string. Can use loadObject(), loadArray().

        // Infer alt text from the SVG title:
        $svg_xml = new \SimpleXMLElement($brand->logo_svg);
        $svg_xml->registerXPathNamespace('svg', 'http://www.w3.org/2000/svg');
        $title = $svg_xml->xpath("//svg:title");
        $brand->alt = (string) $title[0];

        // Look for a URL, if not found, infer from alias:
        if (empty($brand->params->logo_url)) {
            $brand->params->logo_url = 'https://www.npeu.ox.ac.uk/' . $brand->alias;
        }

        // Always override the URL if override URL is set:
        if (!empty($data->override_url)) {
            $brand->params->logo_url = $data->override_url;
        }

        if (!empty($data->limit_height)) {
            $brand->params->limit_height = $data->limit_height;
        }

        return $brand;
    }

}

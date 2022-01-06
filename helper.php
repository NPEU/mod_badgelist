<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_badgelist
 *
 * @copyright   Copyright (C) NPEU 2021.
 * @license     MIT License; see LICENSE.md
 */

defined('_JEXEC') or die;

use Joomla\String\StringHelper;

/**
 * Helper for mod_badgelist
 */
class ModBadgelistHelper
{
    /**
     * Gets the badge data from the brand id
     *
     * @param   \Joomla\Registry\Registry  &$params  module parameters object
     *
     * @return  mixed
     */
    public static function getBrand($data)
    {
        $brand_id = $data->brand_id;
        $db = JFactory::getDBO();

        $query = $db->getQuery(true);
        $query->select('*');
        $query->from('#__brands');
        $query->where('id = ' . $brand_id);
        $db->setQuery($query);
        $brand = $db->loadObject();
        
        if (empty($brand->params)) {
            $brand->params = '{}';
        }
        
        // Process params:
        $brand->params = json_decode($brand->params);
        // Where $params is a JSON string. Can use loadObject(), loadArray().
        
        // Infer alt text from the SVG title:
        $svg_xml = new SimpleXMLElement($brand->logo_svg);
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

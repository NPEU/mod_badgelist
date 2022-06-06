<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_badgelist
 *
 * @copyright   Copyright (C) NPEU 2021.
 * @license     MIT License; see LICENSE.md
 */

defined('_JEXEC') or die;

// Include the badgelist functions only once
JLoader::register('ModBadgelistHelper', __DIR__ . '/helper.php');

$badges_data = $params->get('badges');

$badges = [];
foreach ($badges_data as $badge_data) {
    if (empty($badge_data->brand_id)) {
        continue;
    }
    $badges[] = ModBadgelistHelper::getBrand($badge_data);
}

#$moduleclass_sfx = htmlspecialchars($params->get('moduleclass_sfx'), ENT_COMPAT, 'UTF-8');

require JModuleHelper::getLayoutPath('mod_badgelist', $params->get('layout', 'default'));
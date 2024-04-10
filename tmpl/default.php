<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_badgelist
 *
 * @copyright   Copyright (C) NPEU 2024.
 * @license     MIT License; see LICENSE.md
 */

defined('_JEXEC') or die;


?>
<pre><?php var_dump($badges); exit; ?></pre>
<div>
    <?php foreach ($badges as $badge) : ?>
    <p>
        <a href="<?php echo $badge->params->logo_url; ?>" rel="external noopener noreferrer" target="_blank">
            <img alt="Logo: <?php echo $badge->alt; ?>" height="80" onerror="this.src='<?php echo $badge->logo_png_path; ?>'; this.onerror=null;" src="<?php echo $badge->logo_svg_path; ?>">
        </a>
    </p>
    <?php endforeach; ?>
</div>


<?php $locales = get_option('locale_switcher_locales'); ?>
<?php if ($locales): ?>
    <?php $currentLocale = Zend_Registry::get('bootstrap')->getResource('Locale')->toString(); ?>
    <?php $locales = unserialize($locales); ?>
    <ul class="locale-switcher">
            <li>
                <?php if ($currentLocale == "fr"): ?>
                <span class="badge small">fr</span>
                <?php $url = url('setlocale', array('locale' => "en_US", 'redirect' => current_url($_GET))); ?>
                    <a href="<?php echo $url ; ?>" title="<?php echo locale_description("en_US"); ?>"><span class="badge small" style="background-color: #777777;">en</span></a>
                <?php else: ?>
                    <?php $url = url('setlocale', array('locale' => "fr", 'redirect' => current_url($_GET))); ?>
                    <a href="<?php echo $url ; ?>" title="<?php echo locale_description("fr"); ?>"><span class="badge small" style="background-color: #777777;">fr</span></a>
                    <span class="badge small">en</span>
                <?php endif; ?>
            </li>
    </ul>
<?php endif; ?>

<?php
defined('ABSPATH') || exit;

final class TNet_Community_Composer_View {
    public static function topic(string $markup): string {
        $help = '<details class="composer-formatting-help"><summary>Formatting help</summary><p>'.esc_html(TNet_Community_Composer_Contracts::markdown_help()).'</p></details>';
        $markup = str_replace('<form method="post" enctype="multipart/form-data">', '<form data-composer-view="shared-topic" method="post" enctype="multipart/form-data">', $markup);
        if (strpos($markup, 'composer-formatting-help') === false) $markup = str_replace('</form>', $help.'</form>', $markup);
        return $markup;
    }
}

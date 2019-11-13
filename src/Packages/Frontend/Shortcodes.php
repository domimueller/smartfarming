<?php

namespace Cubetech\Packages\Frontend;

use \Cubetech\Packages\IPackage;

class Shortcodes implements IPackage
{

    public static function run()
    {
        add_filter('do_shortcode_tag', __CLASS__ . '::changeCaptionShortcode', 2, 10);
    }

    public static function changeCaptionShortcode($output, $tag, $attr, $m)
    {
        if ($tag === 'caption') {
            $output = preg_replace('/aria-describedby/', 'data-uk-img aria-describedby', $output);
            $output = preg_replace('/src/', 'data-src', $output);
            return $output;
        }
        return $output;
    }
}

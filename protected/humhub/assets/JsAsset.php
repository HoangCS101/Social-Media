<?php

/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2017 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

namespace humhub\assets;

use humhub\components\assets\AssetBundle;

/**
 * animate.css
 *
 * @author buddha
 */
class JsAsset extends AssetBundle
{
    /**
     * @inheritdoc
     */
    public $defaultDepends = false;

    /**
     * @inheritdoc
     */
    public $sourcePath = '@humhub/assets/template/js';

    /**
     * @inheritdoc
     */

     public $js = [
        'ajax-pagination.js',
        'base-init.js',
        'bootstrap-select.js',
        'Chart.js',
        'chartjs-plugin-deferred.js',
        'circle-progress.js',
        'fullcalendar.js',
        'Headroom.js',
        'imagesloaded.pkgd.js',
        'isotope.pkgd.js',
        'jquery.appear.js',
        'jquery-3.2.1.js',
        'jquery.countTo.js',
        'jquery.gifplayer.js',
        'jquery.magnific-popup.js',
        'jquery.matchHeight.js',
        'jquery.mousewheel.js',
        'jquery.waypoints.js',
        'loader.js',
        'material.min.js',
        'mediaelement-and-player.js',
        'mediaelement-playlist-plugin.min.js',
        'moment.js',
        'perfect-scrollbar.js',
        'popper.min.js',
        'run-chart.js',
        'ScrollMagic.js',
        'simplecalendar.js',
        'smooth-scroll.js',
        'sticky-sidebar.js',
        'svgxuse.js',
        'swiper.jquery.js',
        'velocity.js',
        'webfontloader.min.js'
    ];

}

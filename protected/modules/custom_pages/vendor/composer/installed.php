<?php return array(
    'root' => array(
        'name' => 'humhub/custom_pages',
        'pretty_version' => 'v1.10.10',
        'version' => '1.10.10.0',
        'reference' => '8cb11cdf92a12eb22a1b0437a55df5a741f49979',
        'type' => 'library',
        'install_path' => __DIR__ . '/../../',
        'aliases' => array(),
        'dev' => true,
    ),
    'versions' => array(
        '2amigos/yii2-tinymce-widget' => array(
            'pretty_version' => '1.1.3',
            'version' => '1.1.3.0',
            'reference' => 'a58b7a59a1508f4251a8cea9e010d31c9733bde4',
            'type' => 'yii2-extension',
            'install_path' => __DIR__ . '/../2amigos/yii2-tinymce-widget',
            'aliases' => array(),
            'dev_requirement' => false,
        ),
        'humhub/custom_pages' => array(
            'pretty_version' => 'v1.10.10',
            'version' => '1.10.10.0',
            'reference' => '8cb11cdf92a12eb22a1b0437a55df5a741f49979',
            'type' => 'library',
            'install_path' => __DIR__ . '/../../',
            'aliases' => array(),
            'dev_requirement' => false,
        ),
        'tinymce/tinymce' => array(
            'pretty_version' => 'dev-master',
            'version' => 'dev-master',
            'reference' => 'e4d46be06c8d0d99f542e7199d1283850fb9192c',
            'type' => 'component',
            'install_path' => __DIR__ . '/../tinymce/tinymce',
            'aliases' => array(
                0 => '9999999-dev',
            ),
            'dev_requirement' => false,
        ),
        'yiisoft/yii2' => array(
            'dev_requirement' => false,
            'replaced' => array(
                0 => '*',
            ),
        ),
    ),
);

namespace humhub\modules\example\assets;

use yii\web\AssetBundle;

class ExampleAsset extends AssetBundle
{
    public $publishOptions = [
        'forceCopy' => true
    ];
    public $jsOptions = ['position' => \yii\web\View::POS_END];
    public $sourcePath = '@example/resources';
    public $js = [
        'js/humhub.example.js'
    ];
}
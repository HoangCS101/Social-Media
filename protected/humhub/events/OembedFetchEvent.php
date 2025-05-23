<?php

namespace humhub\events;

use humhub\models\UrlOembed;
use yii\base\Event;

class OembedFetchEvent extends Event
{
    public $url;

    private $providers;

    private $result = null;

    public function getResult()
    {
        return $this->result;
    }

    public function setProviders($providers)
    {
        $this->providers = $providers;
        $this->setResult();
    }

    public function setResult($result = null)
    {
        if ($result) {
            $this->result = $result;
            return;
        }
        $urlOembed = UrlOembed::findOne(['url' => $this->url]);
        if ($urlOembed !== null) {
            $this->result =  trim(preg_replace('/\s+/', ' ', $urlOembed->preview));
        } elseif ($this->providers) {
            $this->result =  trim(preg_replace('/\s+/', ' ', UrlOembed::loadUrl($this->url, $this->getProviderUrl())));
        }
    }

    private function getProviderUrl()
    {
        foreach ($this->providers as $providerName => $provider) {
            if (preg_match($provider['pattern'], $this->url)) {
                return str_replace("%url%", urlencode($this->url), $provider['endpoint']);
            }
        }
        return '';
    }
}

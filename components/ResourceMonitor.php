<?php

namespace app\components;

use yii\base\Component;
use app\models\Requests;
use app\events\ResourceEvent;
use \app\infr\HttpClient\CurlClient;

class ResourceMonitor extends Component
{
    CONST CORRECT_HTTP_STATUS_CODE = 200;

    CONST DURATION_RESOURCE_UNAVAILABLE = array(3, 10, 50, 100, 500);

    CONST EVENT_RESOURCE_UNAVAILABLE = 'ResourceUnavailable';

    CONST EVENT_RESOURCE_AVAILABLE = 'ResourceAvailable';

    /**
     * @var \app\infr\HttpClient\HttpClientInterface
     */
    public $httpClient;

    /**
     * @var array
     */
    public $resources;

    public function __construct(array $config = [])
    {
        $this->httpClient = new CurlClient();

        parent::__construct($config);
    }

    public function requestResource($uri)
    {
        if (empty($uri)) throw new \Exception('URI is not specified');

        $data = [];

        $headers = [
            'Cache-Control: no-cache',
        ];

        $response = $this->httpClient->request($uri, 'GET', $headers, $data);

        return $this->httpClient->getHttpStatusCode();
    }

    public function checkResources()
    {
        if (is_array($this->resources) && !empty($this->resources)) {
            foreach ($this->resources as $uri) {
                $httpStatusCode = $this->requestResource($uri);

                $request = Requests::find()
                    ->where(['uri' => $uri])
                    ->one();

                if ($httpStatusCode !== self::CORRECT_HTTP_STATUS_CODE) {
                    if (!is_null($request)) {
                        $deltaTime = time() - $request->timestmp;
                        if (in_array($deltaTime, self::DURATION_RESOURCE_UNAVAILABLE)) {
                            $event = new ResourceEvent;
                            $event->uri = $uri;
                            $this->trigger(self::EVENT_RESOURCE_UNAVAILABLE, $event);
                        }
                    } else {
                        $request = new Requests();
                        $request->uri = $uri;
                        $request->timestmp = time();
                        $request->status = $httpStatusCode;
                        $request->save();
                    }
                } else {
                    if (!is_null($request)) {
                        $request->delete();

                        $event = new ResourceEvent;
                        $event->uri = $uri;
                        $this->trigger(self::EVENT_RESOURCE_AVAILABLE, $event);
                    }
                }
            }
        }
    }
}
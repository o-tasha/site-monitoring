<?php

namespace app\infr\HttpClient;

/**
 *  HTTP Клиент
 */
class CurlClient implements HttpClientInterface
{
    /**
     * @var resource
     */
    private $ch;

    private $httpStatusCode;

    public function __construct()
    {
        if (!extension_loaded('curl')) {
            throw new \Exception('Curl extension is not loaded');
        }
    }

    /**
     * Устанавливает код ответа
     *
     * @param integer $statusCode
     * @return HttpClientInterface
     */
    public function setHttpStatusCode($statusCode)
    {
        $this->httpStatusCode = $statusCode;
        return $this;
    }

    /**
     * Возвращает код ответа
     *
     * @return mixed
     */
    public function getHttpStatusCode()
    {
        return $this->httpStatusCode;
    }

    /**
     * Инициализирует сеанс cURL
     *
     * @param array $options
     * @return resource
     */
    public function init($options)
    {
        $this->ch = curl_init();
        curl_setopt_array($this->ch, $options);
        return $this->ch;
    }

    /**
     * Выполняет HTTP-запрос
     *
     * @param string $uri
     * @param string $method
     * @param array $headers
     * @param array $data
     * @return mixed
     * @throws \Exception
     */
    public function request($uri, $method = 'GET', array $headers = array(), array $data = array())
    {
        $options = [];
        $options[\CURLOPT_HTTPHEADER] = $headers;
        $options[\CURLOPT_TIMEOUT] = 10;
        $options[\CURLOPT_CONNECTTIMEOUT] = 10;
        $options[\CURLOPT_RETURNTRANSFER] = true;
        $options[\CURLOPT_SSL_VERIFYHOST] = false;
        $options[\CURLOPT_SSL_VERIFYPEER] = false;
        $options[\CURLOPT_FOLLOWLOCATION] = true;
        $data = http_build_query($data, '', '&');

        if ($method == 'GET') {
            $options[\CURLOPT_HTTPGET] = true;
            $options[\CURLOPT_URL] = $uri . '?' . $data;
        } elseif ($method == 'POST') {
            $options[\CURLOPT_POST] = true;
            $options[\CURLOPT_URL] = $uri;
            $options[\CURLOPT_POSTFIELDS] = $data;
        } else {
            throw new \Exception('HTTP method is not supported');
        }

        $ch = $this->init($options);
        $response = curl_exec($ch);

        if ($response === false) {
            $error = curl_error($ch);
            $errorCode = curl_errno($ch);
            throw new \Exception('Curl error: ' . $error, $errorCode);
        }

        $this->setHttpStatusCode(curl_getinfo($ch, \CURLINFO_RESPONSE_CODE));

        curl_close($ch);

        return $response;
    }

}
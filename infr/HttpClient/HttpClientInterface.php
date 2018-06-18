<?php

namespace app\infr\HttpClient;

interface HttpClientInterface
{
    /**
     * Отправляет HTTP запрос
     *
     * @param string $uri
     * @param string $method
     * @param array  $headers
     * @param array  $data
     *
     * @throws \Exception
     *
     * @return array
     */
    public function request($uri, $method = 'GET', array $headers = array(), array $data = array());

    /**
     * Возвращает код ответа
     *
     * @return mixed
     */
    public function getHttpStatusCode();
}
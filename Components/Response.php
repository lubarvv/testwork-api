<?php

namespace API\Components;

/**
 * Class Response
 *
 * Ответ сервера
 *
 * @author Vladimir Lyubar <admin@uclg.ru>
 * @package TestApi
 * @subpackage Components
 */
class Response
{
    /**
     * Поля для ответа
     * @var array
     */
    protected $fields = [];


    /**
     * Конструктор. Добавляет поле result в ответ
     */
    public function __construct()
    {
        $this->addField('result', 'ok');
    }


    /**
     * Добавляет поле для ответа
     * @param string $name
     * @param string $value
     */
    public function addField($name, $value)
    {
        $this->fields[$name] = $value;
    }


    /**
     * Добавляет поля для ответа
     * @param array|\stdClass $fields
     */
    public function addFields($fields)
    {
        $this->fields = array_merge($this->fields, (array)$fields);

    }


    /**
     * Возвращет массив $this->fields, конвертированный в json
     * @return string
     */
    public function getJson()
    {
        return json_encode($this->fields);
    }


    /**
     * Magic-метод, возвращающий json ответа
     * @see Response::getJson()
     * @return string
     */
    public function __toString()
    {
        return $this->getJson();
    }


    /**
     * Отправляет ошибку
     * @param $message
     */
    public function error($message)
    {
        $this->fields['result'] = 'error';
        $this->addField('message', $message);
        $this->send();
    }


    /**
     * Отправляет ответ сервера в виде json
     */
    public function send()
    {
        header('Content-Type: application/json');
        echo $this;
        exit;
    }
}
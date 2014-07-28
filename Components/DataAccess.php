<?php

namespace API\Components;

/**
 * Class DataAccess
 *
 * Обертка для доступа к БД
 *
 * @author Vladimir Lyubar <admin@uclg.ru>
 * @package TestApi
 * @subpackage Components
 */
class DataAccess
{
    /**
     * Соединение с СУБД
     * @var \mysqli
     */
    protected static $connection;


    /**
     * Устанавливает соединение с СУБД, если оно еще не было установлено
     */
    public static function connect()
    {
        if(!(self::$connection instanceof \mysqli)) {
            $config = require '../Config/db.php';
            self::$connection = new \mysqli($config['host'], $config['user'], $config['pass'], $config['database']);
        }
    }


    /**
     * Возвращает название таблицы
     * @return string
     */
    public static function tableName()
    {
        return  strtolower(
            array_reverse(
                explode(
                    '\\',
                    get_called_class()
                )
            )[0]
        );
    }


    /**
     * Возвращает название поля первичного ключа (id)
     * @return string
     */
    public static function primaryKey()
    {
        return 'id';
    }


    /**
     * Возвращает все записи из таблицы
     * @return array|bool
     */
    public static function getAll()
    {
        self::connect();

        $sql = 'SELECT * FROM ' . static::tableName();

        if(!$result = self::$connection->query($sql)) {
            return false;
        }

        $results = [];
        while($row = $result->fetch_object()) {
            $results[] = $row;
        }

        return $results;
    }


    /**
     * Возвращает объект записи по его id
     * @param int $id
     * @return \stdClass|bool
     */
    public static function getById($id)
    {
        self::connect();

        $id = (int)$id;

        $sql = 'SELECT * FROM ' . static::tableName() . ' WHERE ' . static::primaryKey() . ' = ' . $id;

        if(!$result = self::$connection->query($sql)) {
            return false;
        }

        if(!$row = $result->fetch_object()) {
            return false;
        }

        return $row;
    }


    /**
     * Возвращает запись по переданным аттрибутам
     * @param $attributes
     * @return \stdClass|bool
     */
    public static function getByAttributes($attributes)
    {
        self::connect();

        array_walk($attributes, function(&$attribute) {
            $attribute = self::$connection->real_escape_string($attribute);
        });

        $conditions = [];
        foreach($attributes as $name => $value) {
            $conditions[] = "{$name} = '{$value}'";
        }

        $conditions = implode(' AND ', $conditions);

        $sql = 'SELECT * FROM ' . static::tableName() . ' WHERE ' . $conditions . ' LIMIT 1';

        if(!$result = self::$connection->query($sql)) {
            return false;
        }

        if(!$row = $result->fetch_object()) {
            return false;
        }

        return $row;
    }


    /**
     * Возвращает все записи по переданным аттрибутам
     * @param $attributes
     * @return \stdClass|bool
     */
    public static function getAllByAttributes($attributes)
    {
        self::connect();

        array_walk($attributes, function(&$attribute) {
            $attribute = self::$connection->real_escape_string($attribute);
        });

        $conditions = [];
        foreach($attributes as $name => $value) {
            $conditions[] = "{$name} = '{$value}'";
        }

        $conditions = implode(' AND ', $conditions);

        $sql = 'SELECT * FROM ' . static::tableName() . ' WHERE ' . $conditions;

        if(!$result = self::$connection->query($sql)) {
            return false;
        }

        $results = [];

        while($row = $result->fetch_object()) {
            $results[] = $row;
        }

        return $results;
    }


    /**
     * Добавляет запись в таблицу. Возвращает id добавленной записи, или false в случае неудачи
     * @param $fields
     * @return int|bool
     */
    public static function insert($fields)
    {
        self::connect();

        array_walk($fields, function(&$field) {
            $field = self::$connection->real_escape_string($field);
            $field = '\'' . $field . '\'';
        });

        $fieldsNames = implode(', ', array_keys($fields));
        $fieldsValues = implode(', ', $fields);

        $sql = ' INSERT INTO ' . static::tableName() . ' (' . $fieldsNames . ') ' . ' VALUES (' . $fieldsValues . ')';

        if(!self::$connection->query($sql)) {
            echo self::$connection->error, '<br />', $sql;
            return false;
        }

        return self::$connection->insert_id;
    }
}
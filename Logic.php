<?php

namespace API;
use \API\Components\Response;

/**
 * Class Logic
 *
 * Небольшая логика апи
 *
 * @author Vladimir Lyubar <admin@uclg.ru>
 * @package TestApi
 */
class Logic
{
    /**
     * Объект Response
     * @var Response
     */
    private static $response = false;

    /**
     * Проверяет авторизацию юзера
     */
    private static function checkAuth()
    {
        // TODO: сделать авторизацию
//        echo 'Need Auth';
        if(rand(0, 5) === 0) {
            self::$response->error('Не повезло');
        }
    }


    /**
     * Записывает объект Response в self::$response, если он туда еще не записан
     */
    private static function addResponse()
    {
        if(!self::$response) {
            self::$response = new Response();
        }
    }


    /**
     * Возвращает товар
     */
    public static function getProduct()
    {
        self::addResponse();

        self::checkAuth();

        if(!isset($_REQUEST['id']) || empty($_REQUEST['id'])) {
            self::$response->error('Необходимо передать id');
        }

        if($product = \API\DataAccess\Product::getById($_REQUEST['id'])) {
            self::$response->addFields($product);
        } else {
            self::$response->error('Товар не найден');
        }

        self::$response->send();
    }


    /**
     * Возвращает товары в категории
     */
    public static function getProductsByCategory()
    {
        self::addResponse();

        self::checkAuth();

        if(!isset($_REQUEST['category_id']) || empty($_REQUEST['category_id'])) {
            self::$response->error('Необходимо передать category_id');
        }

        if(!$category = \API\DataAccess\Category::getById($_REQUEST['category_id'])) {
            self::$response->error('Категория не найдена');
        }

        if($products = \API\DataAccess\Product::getAllByAttributes(['category_id' => $_REQUEST['category_id']])) {
            self::$response->addField('products', $products);
        } else {
            self::$response->error('Товары не найден');
        }

        self::$response->send();
    }


    /**
     * Возвращает категорию
     */
    public static function getCategory()
    {
        self::addResponse();

        self::checkAuth();

        if(!isset($_REQUEST['id']) || empty($_REQUEST['id'])) {
            self::$response->error('Необходимо передать id');
        }

        if($category = \API\DataAccess\Category::getById($_REQUEST['id'])) {
            self::$response->addFields($category);
        } else {
            self::$response->error('Категория не найден');
        }

        self::$response->send();
    }


    /**
     * Возвращает все категории
     */
    public static function getCategories()
    {
        self::addResponse();

        self::checkAuth();

        if($categories = \API\DataAccess\Category::getAll()) {
            self::$response->addField('categories', $categories);
        } else {
            self::$response->error('Категории не найдены');
        }

        self::$response->send();
    }
}
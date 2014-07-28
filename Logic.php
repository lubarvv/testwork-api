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
        if(!isset($_REQUEST['token']) || empty($_REQUEST['token'])) {
            self::$response->error('Необходимо передать token');
        }

        if(!\API\DataAccess\Token::getByAttributes(['token' => $_REQUEST['token']])) {
            self::$response->error('Token не действителен');
        }
    }


    /**
     * Генерирует токен для доступа к апи
     * @return string
     */
    private static function genToken()
    {
        $token = '';
        $chars = '1234567890qwertyuiopasdfghjklzxcvbnmQWERTYUIOPASDFGHJKLZXCVBNM';

        for($i = 0; $i<64; $i++) {
            $token .= $chars[rand(0, strlen($chars) - 1)];
        }

        return $token;
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


    /**
     * Добавляет категорию
     */
    public static function addCategory()
    {
        self::addResponse();

        self::checkAuth();

        if(!isset($_REQUEST['name']) || empty($_REQUEST['name'])) {
            self::$response->error('Необходимо передать name');
        }

        if($categoryId = \API\DataAccess\Category::insert(['name' => $_REQUEST['name']])) {
            self::$response->addField('id', $categoryId);
        } else {
            self::$response->error('Не удалось добавить категорию');
        }

        self::$response->send();
    }


    /**
     * Добавляет товар
     */
    public static function addProduct()
    {
        self::addResponse();

        self::checkAuth();

        if(!isset($_REQUEST['category_id']) || empty($_REQUEST['category_id'])) {
            self::$response->error('Необходимо передать category_id');
        }

        if(!$category = \API\DataAccess\Category::getById($_REQUEST['category_id'])) {
            self::$response->error('Категория не найдена');
        }

        if(!isset($_REQUEST['name']) || empty($_REQUEST['name'])) {
            self::$response->error('Необходимо передать name');
        }

        if(!isset($_REQUEST['description']) || empty($_REQUEST['description'])) {
            self::$response->error('Необходимо передать description');
        }

        if(!isset($_REQUEST['cost']) || empty($_REQUEST['cost'])) {
            self::$response->error('Необходимо передать cost');
        }

        $data = [
            'category_id' => $_REQUEST['category_id'],
            'name' => $_REQUEST['name'],
            'description' => $_REQUEST['description'],
            'cost' => $_REQUEST['cost'],
        ];

        if($productId = \API\DataAccess\Product::insert($data)) {
            self::$response->addField('id', $productId);
        } else {
            self::$response->error('Не удалось добавить товар');
        }

        self::$response->send();
    }


    /**
     * Добавляет пользователя
     */
    public static function addUser()
    {
        self::addResponse();

        if(!isset($_REQUEST['email']) || empty($_REQUEST['email'])) {
            self::$response->error('Необходимо передать email');
        }

        if($user = \API\DataAccess\User::getByAttributes(['email' => $_REQUEST['email']])) {
            self::$response->error('Этот email уже зарегистрирован');
        }

        if(!isset($_REQUEST['password']) || empty($_REQUEST['password'])) {
            self::$response->error('Необходимо передать password');
        }

        $data = [
            'email' => $_REQUEST['email'],
            'pass' => md5($_REQUEST['password']),
        ];

        if($productId = \API\DataAccess\User::insert($data)) {
            self::$response->addField('id', $productId);
        } else {
            self::$response->error('Не удалось добавить пользователя');
        }

        self::$response->send();
    }


    /**
     * Возвращет токен для доступа, если email и пароль пользовател были верные
     */
    public static function auth()
    {
        self::addResponse();

        if(!isset($_REQUEST['email']) || empty($_REQUEST['email'])) {
            self::$response->error('Необходимо передать email');
        }

        if(!isset($_REQUEST['password']) || empty($_REQUEST['password'])) {
            self::$response->error('Необходимо передать password');
        }

        $conditions = [
            'email' => $_REQUEST['email'],
            'pass' => md5($_REQUEST['password']),
        ];

        if($user = \API\DataAccess\User::getByAttributes($conditions)) {
            $token = self::genToken();
            if(\API\DataAccess\Token::insert(['user_id' => $user->id, 'token' => $token])) {
                self::$response->addField('token', $token);
            } else {
                self::$response->error('Не удалось авторизовать пользователя. Попробуйте позже.');
            }
        } else {
            self::$response->error('Пользователь не найден');
        }

        self::$response->send();
    }
}
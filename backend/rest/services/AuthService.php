<?php
require_once __DIR__ . '/BaseService.php';
require_once __DIR__ . '/../dao/AuthDao.php';
require_once __DIR__ . '/../config.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class AuthService extends BaseService {
    private $auth_dao;

    public function __construct() {
        $this->auth_dao = new AuthDao();
        parent::__construct($this->auth_dao);
    }

    public function getByEmail($email) {
        return $this->auth_dao->getByEmail($email);
    }

    public function register($entity) {
        // basic validation
        if (empty($entity['email']) || empty($entity['password'])) {
            return ['success' => false, 'error' => 'Email and password are required.'];
        }

        // check if email exists
        $email_exists = $this->auth_dao->getByEmail($entity['email']);
        if ($email_exists) {
            return ['success' => false, 'error' => 'Email already registered.'];
        }

        // hash password
        $entity['password'] = password_hash($entity['password'], PASSWORD_BCRYPT);

        // insert user preko BaseService -> BaseDao
        $entity = $this->insert($entity);

        // password ne vraćamo nazad
        if (isset($entity['password'])) {
            unset($entity['password']);
        }

        return ['success' => true, 'data' => $entity];
    }

    public function login($entity) {
        if (empty($entity['email']) || empty($entity['password'])) {
            return ['success' => false, 'error' => 'Email and password are required.'];
        }

        $user = $this->auth_dao->getByEmail($entity['email']);
        if (!$user || !password_verify($entity['password'], $user['password'])) {
            return ['success' => false, 'error' => 'Invalid username or password.'];
        }

        unset($user['password']);

        $jwt_payload = [
            'user' => $user,
            'iat'  => time(),
            'exp'  => time() + (60 * 60 * 24) // 24h
        ];

        $token = JWT::encode(
            $jwt_payload,
            Config::JWT_SECRET(),
            'HS256'
        );

        return [
            'success' => true,
            'data'    => array_merge($user, ['token' => $token])
        ];
    }
}

<?php
namespace frontend\controllers\api;

use common\models\LoginForm;
use common\models\User;
use Yii;
use yii\rest\ActiveController;
use yii\web\UnprocessableEntityHttpException;

class LoginController extends ActiveController {

	public $modelClass = "common/models/User";

	/**
	 * Login the user
	 * @return Array
	 */
	public function actionLogin() {
		$model = new LoginForm();
		$model->load(Yii::$app->request->post(), '');

		if (!$model->validate()) {
			// Validation failed
			$errors = $model->errors;
			return $errors;
		}

		// Validation passed
		$user = User::find()
			->where(['username' => $model->username])
			->one();

		if (!$user) {
			throw new UnprocessableEntityHttpException('Incorrect username or password.');
			$model->addError('username', 'Incorrect username or password.');
			return $model->errors;
		}

		// Validate the password
		if (!Yii::$app->getSecurity()->validatePassword($model->password, $user->password_hash)) {
			throw new UnprocessableEntityHttpException('Incorrect username or password.');
			$model->addError('username', 'Incorrect username or password.');
			return $model->errors;
		}

		// Generate new token and return user
		$user->access_token = md5(uniqid(rand(), true));
		$user->save();

		return [
			'id' => $user->id,
			'username' => $user->username,
			'email' => $user->email,
			'profile' => $user->profile,
			'created_at' => $user->created_at,
			'updated_at' => $user->updated_at,
			'access_token' => $user->access_token,
		];

	}

	public function behaviors() {
		$behaviors = parent::behaviors();

		// remove authentication filter necessary because we need to
		// add CORS filter and it should be added after the CORS
		unset($behaviors['authenticator']);

		// add CORS filter
		$behaviors['corsFilter'] = [
			'class' => '\yii\filters\Cors',
			'cors' => [
				'Origin' => ['*'],
				'Access-Control-Request-Method' => ['GET', 'POST', 'PUT', 'PATCH', 'DELETE', 'HEAD', 'OPTIONS'],
				'Access-Control-Request-Headers' => ['*'],
			],
		];
		return $behaviors;
	}

}
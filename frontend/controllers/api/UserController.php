<?php
namespace frontend\controllers\api;

use Yii;
use yii\filters\auth\HttpBearerAuth;
use yii\rest\ActiveController;
use yii\web\UnauthorizedHttpException;

class UserController extends ActiveController {

	public $modelClass = 'common\resources\UserResource';

	// Adding Cors filter. We could add one by creating a base controller but to save time adding that method here

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

		// re-add authentication filter of your choce
		$behaviors['authenticator'] = [
			'class' => HttpBearerAuth::class,
		];

		// avoid authentication on CORS-pre-flight requests (HTTP OPTIONS method)
		$behaviors['authenticator']['except'] = ['options'];
		$behaviors['authenticator']['only'] = ['view', 'update', 'delete', 'index'];
		return $behaviors;
	}

	public function checkAccess($action, $model = null, $params = []) {
		if (\Yii::$app->getRequest()->headers['authorization']):
			$token_str = \Yii::$app->getRequest()->headers['authorization'];
			$access_token = explode(' ', $token_str)[1];
			if ($access_token !== $model->access_token) {
				throw new UnauthorizedHttpException('Your request was made with invalid credentials.');
			}
		endif;
	}
}
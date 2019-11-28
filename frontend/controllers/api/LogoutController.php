<?php
namespace frontend\controllers\api;

use common\models\User;
use Yii;
use yii\rest\ActiveController;

class LogoutController extends ActiveController {
	public $modelClass = "common/models/User";

	/**
	 * Logout the user
	 * @return Array
	 */
	public function actionLogout() {
		$user = User::find()
			->where(['id' => Yii::$app->request->post()['id']])
			->one();
		$user->access_token = null;
		$user->save();
		return [
			'success' => true,
		];
	}
}
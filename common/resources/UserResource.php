<?php
namespace common\resources;

use common\models\User;

class UserResource extends User {
	public function fields() {
		return [
			'id',
			'username',
			'profile',
			'status',
			'created_at',
			'updated_at',
			'email',
			'access_token',
		];
	}
}
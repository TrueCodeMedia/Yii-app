<?php

use yii\db\Migration;

/**
 * Handles dropping columns from table `{{%user}}`.
 */
class m191126_210624_drop_password_reset_token_column_from_user_table extends Migration {
	/**
	 * {@inheritdoc}
	 */
	public function safeUp() {
		$this->dropColumn('user', 'password_reset_token');
	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown() {
		$this->addColumn('user', 'password_reset_token', $this->string()->unique());
	}
}

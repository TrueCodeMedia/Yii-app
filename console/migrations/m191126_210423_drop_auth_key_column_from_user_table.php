<?php

use yii\db\Migration;

/**
 * Handles dropping columns from table `{{%user}}`.
 */
class m191126_210423_drop_auth_key_column_from_user_table extends Migration {
	/**
	 * {@inheritdoc}
	 */
	public function safeUp() {
		$this->dropColumn('user', 'auth_key');
	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown() {
		$this->addColumn('user', 'auth_key', $this->string(32));
	}
}

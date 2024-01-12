<?php

namespace craft\contentmigrations;

use Craft;
use craft\db\Migration;

/**
 * m231214_152920_create_former_table migration.
 */
class m231214_152920_create_former_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp(): bool
    {
        $this->createTable('former_submissions', [
			'id' => $this->primaryKey(),
			'type' => $this->string(),
	        'template' => $this->string(),
			'language' => $this->string(2),
			'fields_json' => $this->json(),
			'dateCreated' => $this->dateTime()->notNull(),
			'dateUpdated' => $this->dateTime()->notNull(),
        ]);

        return true;
    }

    /**
     * @inheritdoc
     */
    public function safeDown(): bool
    {
        $this->dropTableIfExists('former_submissions');

		return true;
    }
}

<?php

namespace modules\former\records;

use craft\db\ActiveRecord;

class Submission extends ActiveRecord
{
	public function setFields($value): void
	{
		$this->fields_json = json_encode($value);
	}

	public function getFields()
	{
		return json_decode($this->fields_json);
	}

	public function scenarios(): array
	{
		return [
			self::SCENARIO_DEFAULT => [
				'template', 'type', 'language', 'fields'
			]
		];
	}

	public static function tableName(): string
	{
		return 'former_submissions';
	}
}
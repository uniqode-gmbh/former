<?php

namespace modules\former\models;

use Craft;
use craft\base\Model;
use modules\former\contracts\Submission;
use modules\former\enums\TypeEnum;

class GenericSubmission extends Model implements Submission
{
	public string $template;
	public TypeEnum $type;
	public string $language;
	public ?string $postHook;
	public array $fields;

	public function getSubject(): string
	{
		return Craft::t('former/mail', 'subject_' . $this->type->value);
	}

	public function getFailedMessage(): string
	{
		return Craft::t('former/message', 'failed_generic');
	}

	public function getSuccessMessage(): string
	{
		return Craft::t('former/message', $this->type->value);
	}

	public function scenarios(): array
	{
		return [
			self::SCENARIO_DEFAULT => [
				'template', 'type', 'language', 'postHook', 'fields'
			]
		];
	}
}
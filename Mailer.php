<?php

namespace modules\former;

use Craft;
use craft\base\Component;
use craft\mail\Message;
use modules\former\contracts\Submission;

class Mailer extends Component
{
	public const EVENT_BEFORE_SEND = 'before_send';
	public const EVENT_AFTER_SEND = 'after_send';

	public function send(Submission $submission): bool
	{
		return Craft::$app->getMailer()->send(
			(new Message)
				->setTo($this->toAddress())
				->setSubject($submission->subject)
				->setHtmlBody(
					Craft::$app->view->renderTemplate('mail/' . $submission->template, [
						'submission' => $submission
					])
				)
		);
	}

	protected function toAddress(): string
	{
		return Craft::$app->config->custom->email ?? 'info@uniqode.ch';
	}
}
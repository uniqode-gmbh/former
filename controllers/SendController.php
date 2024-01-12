<?php

namespace modules\former\controllers;

use craft\records\Element_SiteSettings;
use craft\web\Controller;

use modules\former\contracts\Submission;
use modules\former\enums\TypeEnum;
use modules\former\models\GenericSubmission;
use modules\former\Module;
use Craft;
use modules\former\records\Submission as SubmissionAR;

class SendController extends Controller
{
	public array|bool|int $allowAnonymous = true;

	public function actionIndex()
	{
		$this->requirePostRequest();

		$submission = $this->getSubmissionFromPost(
			Craft::$app->getRequest()->getBodyParams()
		);

		// Save to database
		$record = new SubmissionAR();
		$record->attributes = $submission->attributes;
		$record->save();

		// Send mail
		$data = ! Module::getInstance()->getMailer()->send($submission)
			? [ 'message' => $submission->errorMessage,
				'icon' => Craft::$app->assetManager->getPublishedUrl('@former/assets/error.svg'),
				'status' => 500]
			: [ 'message' => $submission->successMessage,
				'icon' => Craft::$app->assetManager->getPublishedUrl('@former/assets/success.svg'),
				'status' => 200];

		return $this->asJson([
			'message' => $data['message'],
			'icon' => $data['icon']
		])->setStatusCode($data['status']);
	}

	protected function getSubmissionFromPost(array $post): Submission
	{
		$submission = new GenericSubmission();

		unset($post['CRAFT_CSRF_TOKEN']);

		$post = array_merge($post, [
			'@postHook' => array_key_exists('@postHook', $post) ? $post['postHook'] : null,
			'fields' => array_filter($post, fn($value, $name): bool => ! str_starts_with($name, '@'), ARRAY_FILTER_USE_BOTH)
		]);

		$submission->type = TypeEnum::from($post['@type']);
		$submission->template = $post['@template'];
		$submission->language = $post['@language'];
		$submission->postHook = $post['@postHook'];
		$submission->fields = $post['fields'];

		return $submission;
	}
}
<?php

namespace modules\former;

use Craft;
use craft\base\Event;
use craft\events\RegisterTemplateRootsEvent;
use craft\events\RegisterUrlRulesEvent;
use craft\helpers\App;
use craft\i18n\PhpMessageSource;
use craft\mail\transportadapters\Smtp;
use craft\web\twig\nodes\FallbackNameExpression;
use craft\web\UrlManager;
use craft\web\View;
use yii\base\Module as BaseModule;

/**
 * former module
 *
 * @method static Module getInstance()
 */
class Module extends BaseModule
{
    public function init(): void
    {
        Craft::setAlias('@former', __DIR__);

		$this->registerTranslationCategory();
		$this->registerTemplates();
        $this->registerValidationLocalizationUrl();

	    $this->configureMail();

	    parent::init();
    }

	public function getMailer()
	{
		return new Mailer();
	}

	/**
	 * Configure SMTP server settings for local mail in dev environment
	 *
	 * @return void
	 * @throws \yii\base\InvalidConfigException
	 */
    private function configureMail(): void
	{
		Craft::$app->set('mailer', function()
		{
			$settings = App::mailSettings();

			if ( ! in_array(Craft::$app->config->env, ['production', 'stage']))
			{
				$settings->transportType = Smtp::class;

				$settings->transportSettings = [
					'host' => getenv('SMTP_HOSTNAME'),
					'port' => getenv('SMTP_PORT'),
					'useAuthentication' => (bool)getenv('SMTP_USE_AUTH'),
					'username' => getenv('SMTP_USERNAME'),
					'password' => getenv('SMTP_PASSWORD'),
					'encryptionMethod' => getenv('SMTP_ENCRYPTION_METHOD') ?: null, // 'ssl' or 'tls' or nothing
					'timeout' => getenv('SMTP_TIMEOUT') ?: 10,
				];
			}

			$config = App::mailerConfig($settings);

			return Craft::createObject($config);
		});
	}

	private function registerTemplates(): void
	{
		Event::on(
			View::class,
			View::EVENT_REGISTER_SITE_TEMPLATE_ROOTS,
			function(RegisterTemplateRootsEvent $event) {
				$event->roots['mail'] = __DIR__ . '/templates/mail';
			}
		);
	}

	/**
	 * Register the translation category
	 *
	 * @return void
	 */
	private function registerTranslationCategory(): void
	{
		Craft::$app->i18n->translations['former*'] = [
			'class' => PhpMessageSource::class,
			'sourceLanguage' => 'en',
			'basePath' => __DIR__ . '/translations',
			'allowOverrides' => true,
			'fileMap' => [
				'former/message' => 'messages.php',
				'former/mail' => 'mail.php',
				'former/labels' => 'labels.php'
			]
		];
	}

    private function registerValidationLocalizationUrl()
    {
        Event::on(UrlManager::class, UrlManager::EVENT_REGISTER_SITE_URL_RULES, function(RegisterUrlRulesEvent $event)
        {
            $event->rules['former/former-validation-localizations.js'] = 'former/validation/localizations';
        });
    }
}

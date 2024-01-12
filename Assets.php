<?php

namespace modules\former;

use craft\web\AssetBundle;

class Assets extends AssetBundle
{
	public function init()
	{
		$this->sourcePath = '@former/assets';

		$this->css = ['former.css'];
		$this->js = ['bouncer.js', 'former.js'];

		parent::init();
	}
}
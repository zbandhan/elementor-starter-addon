<?php
namespace Textdomainc\Modules\Starter;

use Textdomainc\Base\Module_Base;

class Module extends Module_Base {

	public function __construct() {
		parent::__construct();

		// This is here for extensibility purposes - go to town and make things happen!
	}

	public function get_name() {
		return 'textdomains';
	}

	public function get_widgets() {
		return [
			'Widget_Starter',
		];
	}

}
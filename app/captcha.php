<?php

class Captcha extends F3instance {
	function jester() {
		Graphics::captcha(150, 60, 5, 'jester');
	}
}

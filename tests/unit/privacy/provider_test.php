<?php

namespace mod_smartlink\unit\privacy;

use mod_smartlink\privacy\provider;

class provider_test extends \basic_testcase
{
    public function test_get_reason(): void
    {
        $metadata = provider::get_reason();
        $this->assertEquals('privacy:metadata', $metadata);
    }
}
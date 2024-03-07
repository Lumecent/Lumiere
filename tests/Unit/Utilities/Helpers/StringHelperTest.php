<?php

namespace Tests\Unit\Utilities\Helpers;

use App\Utilities\Helpers\StringHelper;
use Tests\BaseTest;

class StringHelperTest extends BaseTest
{
    public function testRemoveSpaces(): void
    {
        $wrongString = "  H e  L\n\n
        l \r\r\n\n\r0";

        $this->assertEquals( 'H e L l 0', StringHelper::removeSpaces( $wrongString ) );
    }

    public function testClearHtmlTags(): void
    {
        $wrongString = "<p>Text<span class='text'> test</span></p>";

        $this->assertEquals( 'Text test', StringHelper::clearHtmlTags( $wrongString ) );
    }

    public function testTrim(): void
    {
        $wrongString = '^Test^ ';

        $this->assertEquals( 'Test', StringHelper::trim( $wrongString, '^' ) );
    }
}

<?php

namespace Cosmastech\StatsDClientAdapter\Tests\TagNormalizers\Enums;

enum StringBackedEnum: string
{
    case VALUE_1 = "my-first-value";
    case VALUE_2 = "my-second-value";
}

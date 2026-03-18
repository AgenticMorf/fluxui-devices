<?php

use AgenticMorf\FluxuiDevices\Tests\TestCase;
use AgenticMorf\FluxuiDevices\Tests\UnitTestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

pest()->extend(TestCase::class)->use(RefreshDatabase::class)->in('Feature');
pest()->extend(UnitTestCase::class)->in('Unit');

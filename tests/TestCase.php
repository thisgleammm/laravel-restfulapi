<?php

namespace Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\DB;

abstract class TestCase extends BaseTestCase
{
    use RefreshDatabase;

    protected function setuUp(): void
    {
        parent::setUp();
        DB::delete("delete from addreses");
        DB::delete("delete from contacts");
        DB::delete("delete from users");
    }
}

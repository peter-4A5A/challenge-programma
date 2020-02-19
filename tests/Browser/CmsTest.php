<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class CmsTest extends DuskTestCase
{
    /**
     * A Dusk test example.
     *
     * @return void
     */
    public function testExample()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/cms');
            $browser->clickLink("Pagina toevoegen");
            $browser->value("input[name=page_title]", "Test pagina");
            $browser->value("input[name=url_slug]", "test-pagina");
            $browser->value('.ql-editor', 'Leuke test content');
            $browser->click("input[type=submit]");
            $browser->assertSee("Pagina toevoegen");
        });
    }
}

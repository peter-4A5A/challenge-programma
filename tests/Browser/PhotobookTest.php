<?php

namespace Tests\Browser;

use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class PhotobookTest extends DuskTestCase
{
    /**
     * A Dusk test example.
     *
     * @return void
     */
    public function testVisit()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/photoalbum')
                    ->assertSee('Tijdlijn');
        });
    }

    public function testCreate()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs(User::where('email', 'content@gmail.com')->First())
                ->visit('/photoalbum')
                ->clickLink("Akkoord")
                ->clickLink('Fotoalbum toevoegen')
                ->value("input[name=title]", "TestFotoalbum")
                ->value("textarea[name=description]", "TestDescription")
                ->click("input[type=submit]")
                ->assertSee('Fotoalbum is aangemaakt');
        } );

    }
    public function testView()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/photoalbum')
                ->clickLink("Foto's bekijken")
                ->assertSee("Foto's van Album");
        });
    }
    public function testAdminCreatePhotoalbum()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs(User::where('email', 'admin@gmail.com')->First());
            $browser->visit('/photoalbum')
                ->clickLink("Fotoalbum toevoegen")
                ->value("input[name=title]","Admin test fotoalbum")
                ->value("textarea[name=description]","Admin test fotoalbum")
                ->click("input[type=submit]")
                ->assertSee("Fotoalbum is aangemaakt");
        });
    }

    public function testUploadPhoto(){
        $this->browse(function (Browser $browser) {
            $browser->loginAs(User::where('email', 'admin@gmail.com')->First());
            $browser->visit('/photoalbum')
                ->clickLink("Fotoalbum toevoegen")
                ->value("input[name=title]","Admin test fotoalbum")
                ->value("textarea[name=description]","Admin test fotoalbum")
                ->click("input[type=submit]")
                ->attach('image', public_path('storage/testImage.png'))
                ->click("input[name=submitPhoto]")
                ->assertSee('Uw foto is succesvol opgeslagen.');
        });
    }

    public function testRemovePhoto()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs(User::where('email', 'admin@gmail.com')->First());
            $browser->visit('/photoalbum')
                ->clickLink("Fotoalbum toevoegen")
                ->value("input[name=title]", "Admin test fotoalbum")
                ->value("textarea[name=description]", "Admin test fotoalbum")
                ->click("input[type=submit]")
                ->attach('image', public_path('storage/testImage.png'))
                ->click("input[name=submitPhoto]")
                ->clickLink("Verwijderen")
                ->assertSee('De foto is verwijdert!');
        });
    }

    public function testAddTextToPhoto(){
        $this->browse(function (Browser $browser) {
        $browser->loginAs(User::where('email', 'admin@gmail.com')->First());
        $browser->visit('/photoalbum')
                ->clickLink("Fotoalbum toevoegen")
                ->value("input[name=title]", "Admin test fotoalbum")
                ->value("textarea[name=description]", "Admin test fotoalbum")
                ->click("input[type=submit]")

                ->attach('image', public_path('storage/testImage.png'))
                ->value("div[id=editor]", 'Mooie beschrijving')
                ->click("input[name=submitPhoto]")
                ->assertSee('Uw foto is succesvol opgeslagen.');
        });
    }
}

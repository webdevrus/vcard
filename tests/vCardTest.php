<?php

namespace WebDevRus\vCard\Tests;

use PHPUnit\Framework\TestCase;
use WebDevRus\vCard\vCard;

class vCardTest extends TestCase
{
    /** @var \WebDevRus\vCard\vCard */
    public $vcard;

    /** @var array */
    public $images;

    protected function setUp(): void
    {
        parent::setUp();

        $this->vcard = new vCard;
        $this->images = [
            'url' => [
                'png' => 'https://github.githubassets.com/images/modules/logos_page/GitHub-Mark.png',
                'svg' => 'https://github.githubassets.com/images/modules/site/icons/footer/github-mark.svg',
            ],
            'local' => [
                'png' => __DIR__ . '/media/github-mark.png',
                'svg' => __DIR__ . '/media/github-mark.svg',
            ],
        ];
    }

    /** @test */
    public function set_name_test()
    {
        $this->assertEquals(
            $this->vcard->setName('Doe', 'John')->getContent(),
            $this->getValueWithContent('N:Doe;John')
        );
    }

    /** @test */
    public function set_full_name_test()
    {
        $this->assertEquals(
            $this->vcard->setName('Ivanov', 'Ivan', 'Ivanovich')->getContent(),
            $this->getValueWithContent('N:Ivanov;Ivan;Ivanovich')
        );
    }

    /** @test */
    public function set_nickname_test()
    {
        $this->assertEquals(
            $this->vcard->setNickname('webdevrus')->getContent(),
            $this->getValueWithContent('NICKNAME:webdevrus')
        );
    }

    /** @test */
    public function set_phone_test()
    {
        $this->assertEquals(
            $this->vcard->setPhone('79999999999')->getContent(),
            $this->getValueWithContent('TEL;TYPE=cell,msg:+79999999999')
        );
    }

    /** @test */
    public function set_phone_with_other_types_test()
    {
        $this->assertEquals(
            $this->vcard->setPhone('79999999999', 'work', 'voice')->getContent(),
            $this->getValueWithContent('TEL;TYPE=work,voice:+79999999999')
        );
    }

    /** @test */
    public function set_phone_invalid_test()
    {
        $this->expectException(\WebDevRus\vCard\Exceptions\vCardException::class);
        $this->vcard->setPhone('+7 (999) 999 99-99')->getContent();
    }

    /** @test */
    public function set_image_by_url_test()
    {
        $this->assertEquals(
            $this->vcard->setImageUrl($this->images['url']['png'])->getContent(),
            $this->getValueWithContent('PHOTO;VALUE=uri:' . $this->images['url']['png'])
        );
    }

    /** @test */
    public function set_failed_image_by_url_test()
    {
        $this->expectException(\WebDevRus\vCard\Exceptions\vCardException::class);
        $this->vcard->setImage($this->images['url']['svg'])->getContent();
    }

    /** @test */
    public function set_binary_image_by_url_test()
    {
        $result = $this->vcard->setImage($this->images['url']['png'])->getContent();

        $this->assertTrue((bool) preg_match('/PHOTO;ENCODING=b;TYPE=PNG:(.+)/', $result));
    }

    /** @test */
    public function set_failed_binary_image_by_url_test()
    {
        $this->expectException(\WebDevRus\vCard\Exceptions\vCardException::class);
        $this->vcard->setImage($this->images['url']['svg'])->getContent();
    }

    /** @test */
    public function set_binary_image_by_local_test()
    {
        $result = $this->vcard->setImage($this->images['local']['png'])->getContent();

        $this->assertTrue((bool) preg_match('/PHOTO;ENCODING=b;TYPE=PNG:(.+)/', $result));
    }

    /** @test */
    public function set_failed_binary_image_by_local_test()
    {
        $this->expectException(\WebDevRus\vCard\Exceptions\vCardException::class);
        $this->vcard->setImage($this->images['local']['svg'])->getContent();
    }

    /** @test */
    public function set_birthday_by_true_format_test()
    {
        $this->assertEquals(
            $this->vcard->setBirthday('1990-12-31')->getContent(),
            $this->getValueWithContent('BDAY:1990-12-31')
        );
    }

    /** @test */
    public function set_birthday_by_another_format_test()
    {
        $this->expectException(\WebDevRus\vCard\Exceptions\vCardException::class);
        $this->vcard->setBirthday('01.02.1990')->getContent();
    }

    /** @test */
    public function set_url_test()
    {
        $this->assertEquals(
            $this->vcard->setUrl('https://google.com')->getContent(),
            $this->getValueWithContent('URL:https://google.com')
        );
    }

    /** @test */
    public function set_url_failed_test()
    {
        $this->expectException(\WebDevRus\vCard\Exceptions\vCardException::class);
        $this->vcard->setUrl('google.com')->getContent();
    }

    /** @test */
    public function set_email_test()
    {
        $this->assertEquals(
            $this->vcard->setEmail('web.developers.rus@gmail.com')->getContent(),
            $this->getValueWithContent('EMAIL;TYPE=INTERNET:web.developers.rus@gmail.com')
        );
    }

    /** @test */
    public function set_email_failed_test()
    {
        $this->expectException(\WebDevRus\vCard\Exceptions\vCardException::class);
        $this->vcard->setEmail('webdevrus')->getContent();
    }

    /** @test */
    public function set_other_test()
    {
        $this->assertEquals(
            $this->vcard->setOther('X-SKYPE;TYPE=cell,msg:+79999999999')->getContent(),
            $this->getValueWithContent('X-SKYPE;TYPE=cell,msg:+79999999999')
        );
    }

    /** @test */
    public function check_exception_is_empty_fields_test()
    {
        $this->expectException(\WebDevRus\vCard\Exceptions\vCardException::class);
        $this->vcard->getContent();
    }

    /**
     * Get value with vCard content
     * 
     * @param  string $value
     * @return string
     */
    private function getValueWithContent(string $value): string
    {
        return "BEGIN:VCARD\r\nVERSION:3.0\r\n{$value}\r\nEND:VCARD";
    }
}
<?php

require_once(dirname(__FILE__) . "/IntegrationTestCase.php");

class CompressIntegrationTest extends IntegrationTestCase {

    public function setUp() {
        parent::setUp();
    }

    public function tearDown() {
        clear_settings();
        clear_uploads();
    }

    public function testInvalidCredentialsShouldStillUploadImage()
    {
        $this->set_api_key('1234');
        $this->upload_image(dirname(__FILE__) . '/../fixtures/input-example.png');
        $this->assertContains('input-example',
            self::$driver->findElement(WebDriverBy::xpath('//img[contains(@src, "input-example")]'))->getAttribute('src'));
    }

    public function testInvalidCredentialsShouldShowError()
    {
        $this->set_api_key('1234');
        $this->upload_image(dirname(__FILE__) . '/../fixtures/input-example.png');
        $this->assertContains('Latest error: Credentials are invalid',
            self::$driver->findElement(WebDriverBy::cssSelector('span.error'))->getText());
    }

    public function testShrink() {
        $this->set_api_key('PNG123');
        $this->upload_image(dirname(__FILE__) . '/../fixtures/input-example.png');
        $this->assertContains('Compressed size',
            self::$driver->findElement(WebDriverBy::cssSelector('td.tiny-compress-images'))->getText());
    }

    public function testCompressButton() {
        $this->enable_compression_sizes(array('medium'));
        $this->set_api_key('PNG123');
        $this->upload_image(dirname(__FILE__) . '/../fixtures/input-example.png');
        $this->enable_compression_sizes(array('medium', 'large'));

        self::$driver->get(wordpress('/wp-admin/upload.php'));
        $this->assertContains('Compressed 1 out of 2 sizes',
            self::$driver->findElement(WebDriverBy::cssSelector('td.tiny-compress-images'))->getText());
        self::$driver->findElement(WebDriverBy::cssSelector('td.tiny-compress-images button'))->click();
        self::$driver->wait(2)->until(WebDriverExpectedCondition::textToBePresentInElement(
            WebDriverBy::cssSelector('td.tiny-compress-images'), 'Compressed 2 out of 2 sizes'));
    }

    public function testLimitReached() {
        $this->set_api_key('LIMIT123');
        $this->upload_image(dirname(__FILE__) . '/../fixtures/input-example.png');
        $this->assertContains('You have reached your limit',
            self::$driver->findElement(WebDriverBy::cssSelector('div.error p'))->getText());
    }

    public function testLimitReachedDismisses() {
        $this->set_api_key('LIMIT123');
        $this->upload_image(dirname(__FILE__) . '/../fixtures/input-example.png');
        self::$driver->findElement(WebDriverBy::cssSelector('.tiny-notice button, .tiny-notice a.tiny-dismiss'))->click();
        self::$driver->wait(2)->until(WebDriverExpectedCondition::invisibilityOfElementWithText(
             WebDriverBy::cssSelector('.tiny-dismiss'), 'Dismiss'));

        self::$driver->get(wordpress('/wp-admin/options-media.php'));
        $this->assertEquals(0, count(self::$driver->findElements(WebDriverBy::cssSelector('div.error p'))));
    }

    public function testIncorrectJsonButton() {
        $this->enable_compression_sizes(array());
        $this->upload_image(dirname(__FILE__) . '/../fixtures/input-example.png');
        $this->enable_compression_sizes(array('medium', 'large'));

        $this->set_api_key('JSON1234');
        self::$driver->get(wordpress('/wp-admin/upload.php'));

        self::$driver->findElement(WebDriverBy::cssSelector('td.tiny-compress-images button'))->click();
        self::$driver->wait(2)->until(WebDriverExpectedCondition::textToBePresentInElement(
            WebDriverBy::cssSelector('td.tiny-compress-images'), 'JSON: Syntax error [4]'));
    }
}

<?php

namespace Tests;

use Facebook\WebDriver\Chrome\ChromeOptions;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Illuminate\Support\Collection;
use Laravel\Dusk\TestCase as BaseTestCase;
use PHPUnit\Framework\Attributes\BeforeClass;
use Facebook\WebDriver\Firefox\FirefoxOptions;

abstract class DuskTestCase extends BaseTestCase
{
    /**
     * Prepare for Dusk test execution.
     */
    #[BeforeClass]
    public static function prepare(): void
    {
        if (!static::runningInSail()) {
            if (env('DUSK_BROWSER', 'chrome') === 'firefox') {
                // No need to start ChromeDriver for Firefox tests
                return;
            }
            static::startChromeDriver(['--port=9515']);
        }
    }

    /**
     * Create the RemoteWebDriver instance.
     */
    protected function driver(): RemoteWebDriver
    {
        $browser = env('DUSK_BROWSER', 'chrome');

        switch ($browser) {
            case 'firefox':
                return $this->driverForFirefox();
            case 'chrome':
                return $this->driverForChrome();
            default:
                return $this->driverForChrome();
        }
    }


    protected function driverForChrome(): RemoteWebDriver
    {
        $options = (new ChromeOptions)->addArguments([
            '--window-size=1920,1080',
            '--disable-extensions',
            '--disable-infobars',
            '--disable-dev-shm-usage',
            '--no-sandbox',
            '--disable-blink-features=AutomationControlled',
        ]);

        if (env('DUSK_HEADLESS', false)) {
            $options->addArguments(['--headless=new']);
        }

        return RemoteWebDriver::create(
            env('DUSK_DRIVER_URL', 'http://localhost:9515'),
            DesiredCapabilities::chrome()->setCapability(
                ChromeOptions::CAPABILITY,
                $options
            )
        );
    }

    protected function driverForFirefox(): RemoteWebDriver
    {
        $firefoxOptions = [
            'args' => [
                '--width=1920',
                '--height=1080'
            ]
        ];

        if (env('DUSK_HEADLESS', false)) {
            $firefoxOptions['args'][] = '-headless';
        }

        // Set binary path through capabilities
        $capabilities = DesiredCapabilities::firefox();
        $capabilities->setCapability(
            FirefoxOptions::CAPABILITY,
            ['binary' => env('FIREFOX_PATH', '/usr/bin/firefox')]
        );
        $capabilities->setCapability(
            'moz:firefoxOptions',
            $firefoxOptions
        );

        return RemoteWebDriver::create(
            env('DUSK_FIREFOX_DRIVER_URL', 'http://localhost:4444'),
            $capabilities,
            60000,  // Connection timeout in ms
            60000   // Request timeout in ms
        );
    }
}

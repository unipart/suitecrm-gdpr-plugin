<?php
/**
 * Created by PhpStorm.
 * User: Riccardo De Leo
 */
use Behat\Mink\Element\NodeElement;


/**
 * Class GdprContext
 */
class GdprContext extends LoginContext
{
    /**
     * @BeforeStep
     */
    public function beforeStep()
    {
        $this->getSession()->getDriver()->resizeWindow(1920, 1080);
    }

    /**
     * @When I click install on Unipart plugin row
     */
    public function iClickInstallOnUnipartPluginRow()
    {
        $uploadTable = $this
            ->getSession()
            ->getPage()
            ->find('css', '#upload_table');

        foreach ($uploadTable->findAll('css', 'tr') as $tr) {
            if (is_null($tr->find('xpath', '//div[text()="Unipart Digital - SuiteCRM GDPR Plugin"]'))) {
                continue;
            }

            $tr
                ->find('css', 'form input[type="submit"][value="Install"]')
                ->click();
        }
    }

    /**
     * @When /^I click on "([^"]*)" with text "([^"]*)"$/
     *
     * @param $locator
     * @param $text
     * @throws \InvalidArgumentException
     */
    public function iClickOnWithText($locator, $text)
    {
        $session = $this->getSession();
        $element = $session->getPage()->find(
            'xpath', '//' . $locator . '[text()="' . $text . '"]'
        );

        if (null === $element) {
            throw new \InvalidArgumentException(sprintf('Cannot find text: "%s"', $text));
        }

        $element->click();
    }

    /**
     * @When /^I click on "([^"]*)" with contains text "([^"]*)"$/
     *
     * @param $locator
     * @param $text
     * @throws \InvalidArgumentException
     */
    public function iClickOnWithContainsText($locator, $text)
    {
        $session = $this->getSession();
        $element = $session->getPage()->find(
            'xpath', '//' . $locator . '[text()[contains(.,"' . $text . '")]]'

        );

        if (null === $element) {
            throw new \InvalidArgumentException(sprintf('Cannot find text: "%s"', $text));
        }

        $element->click();
    }

    /**
     * @When /^I hover over the element "([^"]*)"$/
     *
     * @param $locator
     * @throws \InvalidArgumentException
     */
    public function iHoverOverTheElement($locator)
    {
        $session = $this->getSession(); // get the mink session
        $element = $session->getPage()->find('css', $locator); // runs the actual query and returns the element

        // errors must not pass silently
        if (null === $element) {
            throw new \InvalidArgumentException(sprintf('Could not evaluate CSS selector: "%s"', $locator));
        }

        // ok, let's hover it
        $element->mouseOver();
    }

    /**
     * @When /^I double click on "([^"]*)"$/
     *
     * @param $locator
     * @throws \InvalidArgumentException
     */
    public function iDoubleClickOn($locator)
    {
        $session = $this->getSession(); // get the mink session
        $element = $session->getPage()->find('css', $locator); // runs the actual query and returns the element

        // errors must not pass silently
        if (null === $element) {
            throw new \InvalidArgumentException(sprintf('Could not evaluate CSS selector: "%s"', $locator));
        }

        // ok, let's hover it
        $element->doubleClick();
    }

    /**
     * @When /^I click on "([^"]*)"$/
     *
     * @param $locator
     * @throws \InvalidArgumentException
     */
    public function iClickOn($locator)
    {
        $session = $this->getSession(); // get the mink session
        $element = $session->getPage()->find('css', $locator); // runs the actual query and returns the element

        // errors must not pass silently
        if (null === $element) {
            throw new \InvalidArgumentException(sprintf('Could not evaluate CSS selector: "%s"', $locator));
        }

        // ok, let's hover it
        $element->click();
    }

    /**
     * @When /^I should see "([^"]*)" in element "([^"]*)"$/
     *
     * @param $text
     * @param $selector
     * @throws \InvalidArgumentException
     */
    public function iShouldSeeInElement($text, $selector)
    {
        $session = $this->getSession(); // get the mink session
        $element = $session->getPage()->find('css', $selector); // runs the actual query and returns the element

        // errors must not pass silently
        if (null === $element) {
            throw new \InvalidArgumentException(sprintf('Could not evaluate CSS selector: "%s"', $selector));
        }

        // ok, let's hover it
        if ($element->getText() != $text) {
            throw new \InvalidArgumentException(sprintf('Cannot find text: "%s"', $text));
        }
    }

    /**
     * @When /^I should see text "([^"]*)" in element "([^"]*)"$/
     *
     * @param $text
     * @param $selector
     * @throws \InvalidArgumentException
     */
    public function iShouldSeeTextInElement($text, $selector)
    {
        $session = $this->getSession(); // get the mink session
        $element = $session->getPage()->find('css', $selector); // runs the actual query and returns the element

        // errors must not pass silently
        if (null === $element) {
            throw new \InvalidArgumentException(sprintf('Could not evaluate CSS selector: "%s"', $selector));
        }

        if (strpos($element->getText(), $text) === false) {
            throw new \InvalidArgumentException(sprintf('Cannot find text: "%s"', $text));
        }
    }

    /**
     * @When /^I should not see "([^"]*)" in element "([^"]*)"$/
     *
     * @param $text
     * @param $selector
     * @throws \InvalidArgumentException
     */
    public function iShouldNotSeeInElement($text, $selector)
    {
        $session = $this->getSession(); // get the mink session
        $element = $session->getPage()->find('css', $selector); // runs the actual query and returns the element

        // errors must not pass silently
        if (null === $element) {
            throw new \InvalidArgumentException(sprintf('Could not evaluate CSS selector: "%s"', $selector));
        }

        // ok, let's hover it
        if ($element->getText() == $text) {
            throw new \InvalidArgumentException(sprintf('Cannot find text: "%s"', $text));
        }
    }

    /**
     * @When /^I should not see text "([^"]*)" in element "([^"]*)"$/
     *
     * @param $text
     * @param $selector
     * @throws \InvalidArgumentException
     */
    public function iShouldNotSeeTextInElement($text, $selector)
    {
        $session = $this->getSession(); // get the mink session
        $element = $session->getPage()->find('css', $selector); // runs the actual query and returns the element

        // errors must not pass silently
        if (null === $element) {
            throw new \InvalidArgumentException(sprintf('Could not evaluate CSS selector: "%s"', $selector));
        }

        if (strpos($element->getText(), $text) === true) {
            throw new \InvalidArgumentException(sprintf('Cannot find text: "%s"', $text));
        }
    }

    /**
     * @When /^I check in element "([^"]*)"$/
     *
     * @param $selector
     * @throws \InvalidArgumentException
     */
    public function iCheckInElement($selector)
    {
        $session = $this->getSession(); // get the mink session
        $element = $session->getPage()->find('css', $selector); // runs the actual query and returns the element

        // errors must not pass silently
        if (null === $element) {
            throw new \InvalidArgumentException(sprintf('Could not evaluate CSS selector: "%s"', $selector));
        }

        $element->check();
    }

    /**
     * @When /^I scroll on top$/
     */
    public function iScrollOnTop()
    {
        $this->getSession()->executeScript('window.scrollTo(0,0);');
    }

    /**
     * @When /^I scroll on Bottom$/
     */
    public function iScrollOnBottom()
    {
        $this->getSession()->executeScript('window.scrollTo(0,document.documentElement.scrollHeight)');
    }

    /**
     * @When /^I visit privacy preference page$/
     */
    public function iVisitPrivacyPreferencesPage()
    {
        $url = 'http://app/index.php?entryPoint=PrivacyPreferencesEntryPoint&uuid=' .
            $this->getSession()->getPage()->find('css', '#uuid')->getText();

        $this->getSession()->visit($url);
    }

    /**
     * @When /^I click back$/
     */
    public function iClickBack()
    {
        $this->getSession()->getDriver()->back();
    }

    /**
     * @When /^I add "([^"]*)" days to date$/
     *
     * @param  $days
     * @throws Exception
     */
    public function iAddDaysToDate($days)
    {
        $date = new \DateTime();
        $date->add(new DateInterval('P' . $days . 'D'));
        $this->getSession()->getPage()->fillField('lapse_date', $date->format('Y-m-d'));
    }

    /**
     * @When /^I subtract "([^"]*)" days to date$/
     *
     * @param  $days
     * @throws Exception
     */
    public function iSubtractDaysToDate($days)
    {
        $date = new \DateTime();
        $date->sub(new DateInterval('P' . $days . 'D'));
        $this->getSession()->getPage()->fillField('lapse_date', $date->format('Y-m-d'));
    }

}

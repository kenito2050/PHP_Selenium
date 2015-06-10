<?php

//-----------------------------------------------------------------------------------------------------------------------------
// Program Description & Purpose:
//  This program automates the login and entry of data into the following site: www.adactin.com/HotelAppBuild2
//  It logs into the site, creates a hotel booking (using values stored in a csv file), writes the order number and other details to a csv and logs out
//  Program runs as long as there are values in the csv file.
//
// Creator: Ken Villarruel
//
//  Dependencies: 
//  This Program uses:
//  (1) PHP Unit
//  (2) PHP Unit Skeleton Generator to generate PHP Unit Files
//  (3) PHP WebDriver to access web elements
//  (4) Carbon a simple PHP API extension for DateTime
//
// Date: This Program is working as of: 06-03-15
//
// Updates:
// 06-03-15: 
// (1) Created Initial Draft of Script
// (2) Breakpoint issue resolved; Adding the following lines to PHP.ini & restarting  Apache Services resolved issue:
//     xdebug.remote_autostart = "On"
//     xdebug.remote_enable = "On"
// (3) Carbon installed; Carbon is a simple PHP API extension for DateTime
//     $checkin_Date & $checkout_Date values are now generated based the current system date using the Carbon Extension
//     URL: http://carbon.nesbot.com
// (4) Commented out the following line after adding Carbon as it was throwing an error when running the script:
//     require_once dirname(__DIR__) . '/../vendor/autoload.php';
// (5) Wait Statments:
//     Inserted Web Driver Wait Statements to wait for titles of Login Page & Order Confirmation Page
//     Script runs successfully using the wait statements
// (6) Assert Statments:
//     Inserted Assert Statements to verify that tiles on the Login Page & Order Confirmation Page display correctly
//     Script runs successfully using the assert statements
//      
// // TODO:
// (1) Implement the csv reader and writer classes; possibly use parsecsv-for-php
// (2) Remove Hard Coded Username & Password values; Retrieve from a more secure location (perhaps config or xml file)
// (3) Add code to record and store the order number; write order to csv file
// (4) After filling in form and clicking search, block message displays
//-----------------------------------------------------------------------------------------------------------------------------

// This section loads Carbon Extension which is used for generating dates & times
// Next line commented out as it was throwing an error
//require_once dirname(__DIR__) . '/../vendor/autoload.php';
use Carbon\Carbon;
printf("Now: %s", Carbon::now());

class AdactinTest extends PHPUnit_Framework_TestCase

{

    /**
     * @var \RemoteWebDriver
     */
    
    protected $webDriver;
    
    public function __construct() 
    {
        
    }
 
    public function setUp()
            
    {
        $capabilities = array(\WebDriverCapabilityType::BROWSER_NAME => 'firefox');
        $this->webDriver = RemoteWebDriver::create('http://localhost:4444/wd/hub', $capabilities);  
    }
    
    public $url = 'http://adactin.com/HotelAppBuild2';
    
    public function testAdactinHome()
            
    {
        // Declare variables to get Today's Date and Today's Date + (1) Week
        $today = Carbon::today();
        $weekFromToday = Carbon::today()->addWeek();
        //var_dump($this->$Webdriver);
        /* @var $get type */
        $this->webDriver->get($this->url);
       // $this->$WebDriver->get($this->url);
        
        // WAIT STATEMENT
        // wait for at most 10s for the page tile to be 'AdactIn.com - Hotel Reservation System'
        // sleep for 500ms and retries if it the title is not correct.
        $this->webDriver->wait(10, 500)->until(WebDriverExpectedCondition::titleIs('AdactIn.com - Hotel Reservation System'));
        
        // ASSERT STATEMENT
        // Verify that page title contains text "AdactIn.com - Hotel Reservation System"
        $this->assertContains('AdactIn.com - Hotel Reservation System', $this->webDriver->getTitle());
        
        // Find User Name, Password Fields & Submit Button
        $userNameField = $this->webDriver->findElement (WebDriverBy::id('username'));
        $passwordField = $this->webDriver->findElement (WebDriverBy::id('password'));
        $loginButton = $this->webDriver->findElement (WebDriverBy::id('login'));
        
        // Enter User Name
        $userNameField->click();
        $userNameField->sendkeys('qatest2000');
        
        // Enter Password
        $passwordField->click();
        $passwordField->sendkeys('M210H8');
        
        // Click the Login Button
        $loginButton->click();
        
        // Find Location Drop Down and enter value
        $locationDropdown = $this->webDriver->findElement (WebDriverBy::id('location'));
        $locationDropdown->sendkeys('Sydney');
        
        // Find hotel Drop Down and enter value
        $hotelDropdown = $this->webDriver->findElement (WebDriverBy::id('hotels'));
        $hotelDropdown->sendkeys('Hotel Creek');
        
        // Find room type Drop Down and enter value
        $roomTypeDropdown = $this->webDriver->findElement (WebDriverBy::id('room_type'));
        $roomTypeDropdown->sendkeys('Standard');

        // Find Number of Rooms Drop Down and enter value
        $roomNosDropdown = $this->webDriver->findElement (WebDriverBy::id('room_nos'));
        $roomNosDropdown->sendkeys('2 - Two');

        // Find Check In Date Drop Down and enter value
        $checkin_Date = $this->webDriver->findElement (WebDriverBy::id('datepick_in'));
        $checkin_Date->Clear();
        // Format Date in dd/mm/YYYY (because site is Australian)
        $checkin_Date->sendkeys($today->format('d/m/Y'));
        
        // Find Check Out Date Drop Down and enter value
        $checkout_Date = $this->webDriver->findElement (WebDriverBy::id('datepick_out'));
        $checkout_Date->Clear();
        // Format Date in dd/mm/YYYY (because site is Australian)
        $checkout_Date->sendkeys($weekFromToday->format('d/m/Y'));

        // Find Adults Per Room Drop Down and enter value
        $adultsPerRoom = $this->webDriver->findElement (WebDriverBy::id('adult_room'));
        $adultsPerRoom->sendkeys('2 - Two');
        
        // Find Children Per Room Drop Down and enter value
        $childrenPerRoom = $this->webDriver->findElement (WebDriverBy::id('child_room'));
        $childrenPerRoom->sendkeys('2 - Two');

        // Click Search Button
        $searchButton = $this->webDriver->findElement (WebDriverBy::id('Submit'));
        $searchButton->click();

        // Search Criteria Page is returned
        
        // Select the first hotel that is returned
        $firstHotelSelectionResults = $this->webDriver->findElement (WebDriverBy::id('radiobutton_0'));
        $firstHotelSelectionResults->click();

        // Click the Continue Button
        $continueButton = $this->webDriver->findElement (WebDriverBy::id('continue'));
        // Set break point on the continue button
        // issue with allow access warning message
        
        $continueButton->click();

        // Allow Access Warning displays; Need to find workaround
        // 
        // Book a Hotel Page

        // Find First Name
        $firstName = $this->webDriver->findElement (WebDriverBy::id('first_name'));
        $firstName->sendkeys('Mike');

        // Find Last Name
        $lastName = $this->webDriver->findElement (WebDriverBy::id('last_name'));
        $lastName->sendkeys('Jackson');

        // Find Address and enter text
        $address = $this->webDriver->findElement (WebDriverBy::id('address'));
        $address->sendkeys('500 Brand Blvd \n Glendale, CA \n 91203');

        // Find Credit Card Number Field
        $creditCardNumber = $this->webDriver->findElement (WebDriverBy::id('cc_num'));
        $creditCardNumber->sendkeys('1111111111111111');

        // Find Credit Card Type
        $creditCardType = $this->webDriver->findElement (WebDriverBy::id('cc_type'));
        $creditCardType->sendkeys('VISA');

        // Find Credit Card Expiration Month
        $creditCardExpirationMonth = $this->webDriver->findElement (WebDriverBy::id('cc_exp_month'));
        $creditCardExpirationMonth->sendkeys('June');

        // Find Credit Card Expiration Year
        $creditCardExpirationYear = $this->webDriver->findElement (WebDriverBy::id('cc_exp_year'));
        $creditCardExpirationYear->sendkeys('2016');

        // Find Credit Card CVV
        $creditCard_CVV = $this->webDriver->findElement (WebDriverBy::id('cc_cvv'));
        $creditCard_CVV->sendkeys('479');

        // Click the Continue Button
        $bookNowButton = $this->webDriver->findElement (WebDriverBy::id('book_now'));
        $bookNowButton->click();

        // WAIT STATEMENT
        // wait for at most 10s for the page tile to be 'AdactIn.com - Hotel Booking Confirmation'
        // sleep for 500ms and retries if it the title is not correct.
        $this->webDriver->wait(10, 500)->until(WebDriverExpectedCondition::titleIs('AdactIn.com - Hotel Booking Confirmation'));
        
        // ASSERT STATEMENT
        // Verify that page title contains text "AdactIn.com - Hotel Booking Confirmation"
        $this->assertContains('AdactIn.com - Hotel Booking Confirmation', $this->webDriver->getTitle());
        
        //TODO: Retrieve and Store the Order Number: Note this code is C# not PHP;
        // Read in Order Number Value
        /*
        ReadOnlyCollection<IWebElement> ec = driver.FindElements(By.Id("order_no"));
        for (int k = 0; k < ec.Count; k++)
        {
            //Console.WriteLine("Order Number" + " " + ec[k].GetAttribute("value"));
            _orderNumber = ec[k].GetAttribute("value");
        }
         * 
         */
    }
    
    public function tearDown()
    {
        $this->webDriver->close();
    }
}
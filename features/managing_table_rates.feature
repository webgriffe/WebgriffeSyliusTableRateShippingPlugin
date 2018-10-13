@managing_table_rates
Feature: Managing table rates
  In order to define shipping table rates
  As an Administrator
  I want to browse, create, update and delete table rates

  Background:
    Given I am logged in as an administrator
    And the store operates on a single channel in "United States"

  @ui
  Scenario: Browsing an empty list of table rates
    When I am browsing the list of table rates
    Then I should see zero table rates in the list

  @ui
  Scenario: Browsing the list of table rates
    Given the store has a shipping table rate "East Coast Rates" for currency "USD"
    And the store has also a shipping table rate "West Coast Rates" for currency "USD"
    When I am browsing the list of table rates
    Then I should see 2 table rates in the list
    And I should see the "East Coast Rates" table rate in the list
    And I should see the "West Coast Rates" table rate in the list

  @ui @javascript
  Scenario: Adding a new table rate
    When I add a shipping table rate named "New Rates" for currency "USD"
    And I am browsing the list of table rates
    Then I should see 1 table rate in the list
    And I should see the "New Rates" table rate in the list

  @ui
  Scenario: Deleting a table rate
    Given the store has a shipping table rate "East Coast Rates" for currency "USD"
    When I am browsing the list of table rates
    Then I should see 1 table rate in the list
    When I delete the "East Coast Rates" table rate
    And I am browsing the list of table rates
    Then I should see zero table rates in the list

  @ui
  Scenario: Updating a table rate
    Given the store has a shipping table rate "East Coast Rates" for currency "USD"
    And this shipping table rate has a rate "$5" for shipments up to 1000 kg
    And I want to modify the "East Coast Rates" table rate
    When I change its name to "Edited Rates"
    And I save my changes
    Then I should be notified that it has been successfully edited
    And this shipping table rate name should be "Edited Rates"

  @ui @javascript
  Scenario: Adding weight rates into a table rate
    Given the store has a shipping table rate "East Coast Rates" for currency "USD"
    And I want to modify the "East Coast Rates" table rate
    When I add a new rate of "$5" for shipments up to 5 kg
    And I add a new rate of "$10" for shipments up to 20 kg
    And I save my changes
    Then I should be notified that it has been successfully edited
    And this shipping table rate should have 2 rates

  @ui @javascript
  Scenario: Validating a table rate
    When I try to add a new shipping table
    And I specify its code as "VALIDATE_ME"
    And I specify its currency as "USD"
    And I add a new rate of "$10" for shipments up to 20 kg
    But I do not specify its name
    And I try to add it
    Then I should be notified that name is required
    When I try to add a new shipping table
    And I specify its name as "Validate Me"
    And I specify its currency as "USD"
    And I add a new rate of "$10" for shipments up to 20 kg
    But I do not specify its code
    And I try to add it
    Then I should be notified that code is required
    When I try to add a new shipping table
    And I specify its code as "VALIDATE_ME"
    And I specify its name as "Validate Me"
    And I add a new rate of "$10" for shipments up to 20 kg
    But I do not specify its currency
    And I try to add it
    Then I should be notified that currency is required
    When I try to add a new shipping table
    And I specify its code as "VALIDATE_ME"
    And I specify its name as "Validate Me"
    And I specify its currency as "USD"
    But I do not specify any rate
    And I try to add it
    Then I should be notified that at least one rate is required

  @ui
  Scenario: Trying to change table rate code (even if its field is disabled)
    Given the store has a shipping table rate "East Coast Rates" for currency "USD"
    And this shipping table rate has a rate "$5" for shipments up to 1000 kg
    And I want to modify the "East Coast Rates" table rate
    Then the code field should be disabled
    When I change its code to "ANOTHER_CODE"
    And I save my changes
    Then the "East Coast Rates" table rate should still have code "EAST_COAST_RATES"

  @ui
  Scenario: Trying to change table rate currency (even if its field is disabled)
    Given the store also operates on another channel named "Europe" in "EUR" currency
    And the store has a shipping table rate "East Coast Rates" for currency "USD"
    And this shipping table rate has a rate "$5" for shipments up to 1000 kg
    And I want to modify the "East Coast Rates" table rate
    Then the currency field should be disabled
    When I change its currency to "EUR"
    And I save my changes
    Then the "East Coast Rates" table rate should still have "USD" currency

  @ui @javascript
  Scenario: Creating two table rates with the same code
    Given the store has a shipping table rate "East Coast Rates" for currency "USD"
    When I add a shipping table rate named "East Coast Rates" for currency "USD"
    Then I should be notified that code has to be unique

  @ui
  Scenario: Deleting a table rate which is already in use
    Given the store has a shipping table rate "East Coast Rates" for currency "USD"
    And the store has "East Coast Shipping" shipping method using "East Coast Rates" table rate for "United States" channel
    When I am browsing the list of table rates
    Then I should see 1 table rate in the list
    When I delete the "East Coast Rates" table rate
    Then I should be notified that the table rate couldn't be deleted because is already used by the "East Coast Shipping" shipping method
    And the "East Coast Rates" shipping table rate should still be there

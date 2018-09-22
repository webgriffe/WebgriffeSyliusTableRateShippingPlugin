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

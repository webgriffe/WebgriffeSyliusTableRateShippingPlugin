@managing_table_rates
Feature: Managing table rates
  In order to define shipping table rates
  As an Administrator
  I want to browse, create, update and delete table rates

  Background:
    Given I am logged in as an administrator

  @ui
  Scenario: Browsing an empty list of table rates
    When I am browsing the list of table rates
    Then I should see zero table rates in the list

  @ui @todo
  Scenario: Browsing the list of suppliers
    Given there is a supplier "Sylius"
    And there is also a supplier "Webgriffe"
    When I am browsing the list of suppliers
    Then I should see 2 suppliers in the list
    And I should see the "Sylius" supplier in the list
    And I should see the "Webgriffe" supplier in the list

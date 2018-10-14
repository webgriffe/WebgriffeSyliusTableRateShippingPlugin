@table_rate_shipping_fee
Feature: Seeing correct shipping fees for table rate based shipping methods
    In order to get a proper margin on shipping cost
    As a Store Owner
    I want to charge different fees for different shipment weights

    Background:
        Given the store operates on a single channel in "United States"
        And the store has a product "Bottle of water" which weights 1 kg
        And the store has a shipping table rate "Weight-based" for currency "USD"
        And it has a rate "$5" for shipments up to 5 kg
        And it has a rate "$10" for shipments up to 20 kg
        And the store has "Basic" shipping method using "Weight-based" table rate for "United States" channel
        And I am a logged in customer

    @ui
    Scenario: Seeing correct shipping fee for the light shipment
        When I add product "Bottle of water" to the cart
        Then my cart shipping total should be "$5.00"

    @ui
    Scenario: Seeing correct shipping fee for the exact upper limit of the lighter shipment
        When I add 5 products "Bottle of water" to the cart
        Then my cart shipping total should be "$5.00"

    @ui
    Scenario: Seeing correct shipping fee for the heavier shipment
        When I add 15 products "Bottle of water" to the cart
        Then my cart shipping total should be "$10.00"

    @ui
    Scenario: Seeing no shipping methods for too heavy shipment
        When I add 25 products "Bottle of water" to the cart
        Then my cart shipping total should be "$0.00"
        And I should have no shipping methods available to choose from

    @ui
    Scenario: Seeing no shipping methods for too heavy shipment when increasing product quantity from the shopping cart
        When I add product "Bottle of water" to the cart
        Then my cart shipping total should be "$5.00"
        When I change "Bottle of water" quantity to "25"
        Then my cart shipping total should be "$0.00"
        And I should have no shipping methods available to choose from

@table_rate_shipping_fee
Feature: Seeing correct shipping fees for table rate based shipping methods
    In order to get a proper margin on shipping cost
    As a Store Owner
    I want to charge different fees for different shipment weights

    @ui
    Scenario: Seeing correct shipping fee for a given weight
        Given the store operates on a single channel in "United States"
        And the store has a product "Bottle of water" which weights 1 kg
        And the store has a shipping table rate "Weight-based" for currency "USD"
        And this shipping table rate has a fee "$5" for shipments up to 5 kg
        And this shipping table rate has a fee "$10" for shipments up to 20 kg
        And the store has "Basic" shipping method using "Weight-based" table rate for "United States" channel
        And I have product "Bottle of water" in the cart
        When I proceed selecting "Basic" shipping method
        And my cart shipping total should be "$5.00"

@security @login
Feature: Bigfoot login
    As a bigfoot user
    I attempt to log in the back office

Scenario: 1 Login with good user and pass
    Given I am on "/admin/login"
    Then I fill in the following:
        | bigfoot_login_username | admin |
        | bigfoot_login_password | admin |
    Then I press "form.bigfoot_login.submit"
    And I should see "Administrator"

Scenario: 2 Login with bad credentials
    Given I am on "/admin/login"
    Then I fill in the following:
        | bigfoot_login_username | adminFake |
        | bigfoot_login_password | adminFake |
    Then I press "form.bigfoot_login.submit"
    And I should see "form.bigfoot_login.exception.bad_credentials"

Scenario: 3 Check forgot password back link and login back link
    Given I am on "/admin/login"
    Then I follow "form.bigfoot_forgot_password.back_link"
    Then I should see "form.bigfoot_forgot_password.title"
    Then I follow "form.bigfoot_login.back_link"
    And I should see "form.bigfoot_login.submit"

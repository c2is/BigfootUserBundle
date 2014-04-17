@security @forgot-password
Feature: Bigfoot forgot password
    As a bigfoot user
    I attempt to retrieve my password

@javascript
Scenario: 1 Email sent
    Given I am on "/admin/login"
    Then I follow "form.bigfoot_forgot_password.back_link"
    Then I fill in the following:
        | bigfoot_forgot_password_email | y.latti@c2is.fr |
    Then I press "form.bigfoot_forgot_password.submit"
    And I wait until i see "form.bigfoot_forgot_password.success"

@javascript
Scenario: 2 Email doesn't exist
    Given I am on "/admin/login"
    Then I follow "form.bigfoot_forgot_password.back_link"
    Then I fill in the following:
        | bigfoot_forgot_password_email | non-existant-email@c2is.fr |
    Then I press "form.bigfoot_forgot_password.submit"
    And I wait until i see "form.bigfoot_forgot_password.children.email.error.non_existing"

@javascript
Scenario: 3 Email already sent
    Given I am on "/admin/login"
    Then I follow "form.bigfoot_forgot_password.back_link"
    Then I fill in the following:
        | bigfoot_forgot_password_email | y.latti@c2is.fr |
    Then I press "form.bigfoot_forgot_password.submit"
    Then I wait until i see "form.bigfoot_forgot_password.success"
    Then I fill in the following:
        | bigfoot_forgot_password_email | y.latti@c2is.fr |
    Then I press "form.bigfoot_forgot_password.submit"
    And I wait until i see "form.bigfoot_forgot_password.children.email.error.already_sent"

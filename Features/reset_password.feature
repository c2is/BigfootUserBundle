@security @reset-password
Feature: Bigfoot reset password
    As a bigfoot user
    I attempt to reset my password

Scenario: 1 Reset password with a valid token
    Given I am on "/admin/login"
    Then I follow "form.bigfoot_forgot_password.back_link"
    Then I fill in the following:
        | bigfoot_forgot_password_email | y.latti@c2is.fr |
    Then I press "form.bigfoot_forgot_password.submit"
    Then I go to reset password page with "y.latti@c2is.fr" and a "valid-token"
    And I should see "form.bigfoot_reset_password.title"

Scenario: 2 Reset password with an non-existing token
    Given I am on "/admin/login"
    Then I go to "/admin/reset-password/non-existing-token"
    And I should not see "form.bigfoot_reset_password.title"

Scenario: 3 Reset password with an exipired token
    Given I am on "/admin/login"
    Then I follow "form.bigfoot_forgot_password.back_link"
    Then I fill in the following:
        | bigfoot_forgot_password_email | y.latti@c2is.fr |
    Then I press "form.bigfoot_forgot_password.submit"
    Then I go to reset password page with "y.latti@c2is.fr" and a "expired-token"
    And I should not see "form.bigfoot_reset_password.title"

Scenario: 4 Reset password with same entries
    Given I am on "/admin/login"
    Then I follow "form.bigfoot_forgot_password.back_link"
    Then I fill in the following:
        | bigfoot_forgot_password_email | y.latti@c2is.fr |
    Then I press "form.bigfoot_forgot_password.submit"
    Then I go to reset password page with "y.latti@c2is.fr" and a "valid-token"
    Then I should see "form.bigfoot_reset_password.title"
    Then I fill in the following:
        | bigfoot_reset_password_plainPassword_first  | zzzzzz |
        | bigfoot_reset_password_plainPassword_second | zzzzzz |
    Then I press "form.bigfoot_reset_password.submit"
    And I should see "form.bigfoot_reset_password.success"
    And I should see "Yehya"

Scenario: 5 Reset password with different entries
    Given I am on "/admin/login"
    Then I follow "form.bigfoot_forgot_password.back_link"
    Then I fill in the following:
        | bigfoot_forgot_password_email | y.latti@c2is.fr |
    Then I press "form.bigfoot_forgot_password.submit"
    Then I go to reset password page with "y.latti@c2is.fr" and a "valid-token"
    And I should see "form.bigfoot_reset_password.title"
    Then I fill in the following:
        | bigfoot_reset_password_plainPassword_first  | zzzzzz |
        | bigfoot_reset_password_plainPassword_second | zzzzz  |
    Then I press "form.bigfoot_reset_password.submit"
    And I should see "form.bigfoot_reset_password.plainPassword.invalid"
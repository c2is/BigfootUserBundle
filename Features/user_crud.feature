@user
Feature: Bigfoot user
    As a bigfoot admin
    I attempt to create update and delete a user

Scenario: 1 Create user
    Given I am logged in the back office with "admin" / "admin"
    Then I follow "bigfoot.sidebar_menu.user.title"
    Then I follow "bigfoot.sidebar_menu.user.level_1.user"
    Then I follow "bigfoot-add-entity"
    And I should see "form.bigfoot_user.children.username.label"
    And I should see "form.bigfoot_user.children.email.label"
    Then I fill in the following:
        | bigfoot_user_username             | john            |
        | bigfoot_user_email                | j.smith@c2is.fr |
        | bigfoot_user_fullName             | John SMITH      |
        | bigfoot_user_plainPassword_first  | aaaaaa          |
        | bigfoot_user_plainPassword_second | aaaaaa          |
    Then I press "bigfoot.form.submit"
    And I should see "bigfoot.form.success"
    And I should see "bigfoot.index.back_link"
    And I should see "bigfoot.index.add_new"

Scenario: 3 Update user
    Given I am logged in the back office with "admin" / "admin"
    Then I follow "bigfoot.sidebar_menu.user.title"
    Then I follow "bigfoot.sidebar_menu.user.level_1.user"
    Then I edit item "3"
    And I should see "form.bigfoot_user.children.username.label"
    And I should see "form.bigfoot_user.children.email.label"
    Then I fill in the following:
        | bigfoot_user_username             | johnBis         |
        | bigfoot_user_email                | j.smith@c2is.fr |
        | bigfoot_user_fullName             | John SMITH      |
        | bigfoot_user_plainPassword_first  | aaaaaa          |
        | bigfoot_user_plainPassword_second | aaaaaa          |
    Then I press "bigfoot.form.submit"
    And I should see "bigfoot.form.success"
    And I should see "bigfoot.index.back_link"
    And I should see "bigfoot.index.add_new"
    And the "bigfoot_user_username" field should contain "johnBis"

@javascript
Scenario: 4 Delete user
    Given I am logged in the back office with "admin" / "admin"
    Then I follow "bigfoot.sidebar_menu.user.title"
    Then I follow "bigfoot.sidebar_menu.user.level_1.user"
    And I should see "jean"
    Then I delete item "3"
    And I wait "200"
    Then I press "OK"
    #And I should see "bigfoot.modal.cancel"
    #And I should see "bigfoot.modal.accept"
    And I should not see "jean"

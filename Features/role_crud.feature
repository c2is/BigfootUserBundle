@user
Feature: Bigfoot role
    As a bigfoot admin
    I attempt to create update and delete a role

Scenario: 1 Create role
    Given I am logged in the back office with "admin" / "admin"
    Then I follow "bigfoot.sidebar_menu.user.title"
    Then I follow "bigfoot.sidebar_menu.user.level_1.role"
    Then I follow "bigfoot-add-entity"
    And I should see "form.bigfoot_role.children.name.label"
    And I should see "form.bigfoot_role.children.label.label"
    Then I fill in the following:
        | bigfoot_role_name  | ROLE_TEST |
        | bigfoot_role_label | Tester    |
    Then I press "bigfoot.form.submit"
    And I should see "bigfoot.form.success"
    And I should see "bigfoot.index.back_link"
    And I should see "bigfoot.index.add_new"

Scenario: 3 Update role
    Given I am logged in the back office with "admin" / "admin"
    Then I follow "bigfoot.sidebar_menu.user.title"
    Then I follow "bigfoot.sidebar_menu.user.level_1.role"
    Then I edit item "2"
    And I should see "form.bigfoot_role.children.name.label"
    And I should see "form.bigfoot_role.children.label.label"
    Then I fill in the following:
        | bigfoot_role_name  | ROLE_TEST |
        | bigfoot_role_label | TesterBis |
    Then I press "bigfoot.form.submit"
    And I should see "bigfoot.form.success"
    And I should see "bigfoot.index.back_link"
    And I should see "bigfoot.index.add_new"
    And the "bigfoot_role_label" field should contain "TesterBis"

@javascript
Scenario: 4 Delete role
    Given I am logged in the back office with "admin" / "admin"
    Then I follow "bigfoot.sidebar_menu.user.title"
    And I wait "200"
    Then I follow "bigfoot.sidebar_menu.user.level_1.role"
    Then I follow "bigfoot-add-entity"
    And I should see "form.bigfoot_role.children.name.label"
    And I should see "form.bigfoot_role.children.label.label"
    Then I fill in the following:
        | bigfoot_role_name  | ROLE_TEST |
        | bigfoot_role_label | Tester |
    Then I press "bigfoot.form.submit"
    And I should see "bigfoot.form.success"
    And I should see "bigfoot.index.back_link"
    And I should see "bigfoot.index.add_new"
    Then I follow "bigfoot.index.back_link"
    And I should see "Tester"
    Then I delete item "3"
    And I wait "200"
    Then I press "OK"
    #And I should see "bigfoot.modal.cancel"
    #And I should see "bigfoot.modal.accept"
    And I should not see "Tester"

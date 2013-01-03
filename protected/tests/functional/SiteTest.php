<?php

class SiteTest extends WebTestCase {

    public $fixtures = array(
        'users' => 'UserAccount',
        'roles' => 'Role',
        'status' => 'Status',
        'artistePortfolios' => 'ArtistePortfolio',
        'productionPortfolio' => 'ProductionPortfolio',
        'photo' => 'Photo',
        'artistePortfolioPhoto' => 'ArtistePortfolioPhoto',
        'skill' => 'Skill',
        'artistePortfolioSkills' => 'ArtistePortfolioSkills',
        'profession' => 'Profession',
        'artistePortfolioProfession' => 'ArtistePortfolioProfession',
        'language' => 'Language',
        'languageProficiency' => 'LanguageProficiency',
        'spokenLanguage' => 'SpokenLanguage',
        'ethnicity' => 'Ethnicity',
        'passwordResetToken' => 'passwordResetToken',
    );

    // Test Artiste Sign Up with new username: smu.timberwerkz@gmail.com and password 123
    public function testSignUp() {
        $this->open("/timberwerkz/site");
        $this->windowMaximize();
        $this->type("id=UserAccount_email", "smu.timberwerkz@gmail.com");
        $this->type("id=UserAccount_email2", "smu.timberwerkz@gmail.com");
        $this->type("id=UserAccount_password", "123");
        $this->type("id=UserAccount_password2", "123");
        $this->clickAndWait("id=submit_register");
        try {
            $this->assertTrue($this->isTextPresent("Applications"));
        } catch (PHPUnit_Framework_AssertionFailedError $e) {
            array_push($this->verificationErrors, $e->toString());
        }
        $this->click("link=Log Out");

        // Test Production House Sign Up with new username: prod2@prod.com and password 123
        $this->open("/timberwerkz/site");
        $this->clickAndWait("link=Production House");
        $this->type("id=UserAccount_email", "prod2@prod.com");
        $this->type("id=UserAccount_email2", "prod2@prod.com");
        $this->type("id=UserAccount_password", "123");
        $this->type("id=UserAccount_password2", "123");
        $this->clickAndWait("css=div.row.buttons > button[name=\"yt0\"]");
        try {
            $this->assertTrue($this->isTextPresent("Casting Calls"));
        } catch (PHPUnit_Framework_AssertionFailedError $e) {
            array_push($this->verificationErrors, $e->toString());
        }
        $this->click("link=Log Out");
    }

    //Test Log In with existing username:timberwerkz.1@gmail.com and password 123
    public function testLogIn() {
        $this->open("/timberwerkz/site");
        $this->windowMaximize();
        $this->assertTextPresent('Sign Up');
        $this->type("id=LoginForm_email", "timberwerkz.1@gmail.com");
        $this->type("id=LoginForm_password", "123");
        $this->clickAndWait("id=submit_login");
        $this->assertTextPresent('Applications');
        $this->clickAndWait("link=Log Out");
    }

    // Test resetting of password of account "timberwerkz.1@gmail.com" in the case of user forgetting password.
    // A password reset token is sent to the user's email by smu.timberwerkz.dev
    public function testResetPassword() {
        //Click login button to get to page with reset password option
        $this->open("/timberwerkz/");
        $this->windowMaximize();
        $this->clickAndWait("id=submit_login");
        $this->assertTextPresent("Please fill out the following form with your login credentials:");
        $this->click("id=resetPassword");
        $this->waitForElementPresent("id=ResetPasswordForm_email");
        $this->assertTextPresent("Reset Password");
        $this->type("id=ResetPasswordForm_email", "timberwerkz.1@gmail.com");
        $this->click("link=Submit");
        $this->open("https://www.gmail.com/");
        $this->type("id=Email", "timberwerkz.1");
        $this->click("id=Passwd");
        $this->type("id=Passwd", "t1mb3rw3rk5");
        $this->click("id=signIn");
        $this->waitForPageToLoad("30000");
        $this->captureScreenshot("C:\wamp\www\screenshots\ResetPwdTokenEmail.png");
    }

    // Test changing of password of account "timberwerkz@timberwerkz.com" from 123 to 1234
    public function testChangePassword() {
        $this->open("/timberwerkz/site");
        $this->windowMaximize();
        $this->assertTextPresent('Sign Up');
        $this->type("id=LoginForm_email", "timberwerkz@timberwerkz.com");
        $this->type("id=LoginForm_password", "123");
        $this->clickAndWait("id=submit_login");
        $this->assertTextPresent('Applications');
        $this->click("link=Account");
        $this->waitForPageToLoad("30000");
        $this->assertTextPresent('Security');
        $this->click("id=changePassword");
        $this->waitForElementPresent("id=ChangePasswordForm_oldPassword");
        $this->type("id=ChangePasswordForm_oldPassword", "123");
        $this->type("id=ChangePasswordForm_newPassword", "1234");
        $this->type("id=ChangePasswordForm_newPassword2", "1234");
        $this->click("link=Submit");
        $this->clickAndWait("link=Log Out");
        $this->assertTextPresent('Sign Up');
        $this->type("id=LoginForm_email", "timberwerkz@timberwerkz.com");
        $this->type("id=LoginForm_password", "1234");
        $this->clickAndWait("id=submit_login");
        $this->assertTextPresent('Applications');
    }

    // Test changing of email of account "timberwerkz@timberwerkz.com" to "timberwerkzB@timberwerkz.com"
    public function testChangeEmail() {
        $this->open("/timberwerkz/site");
        $this->windowMaximize();
        $this->assertTextPresent('Sign Up');
        $this->type("id=LoginForm_email", "timberwerkz@timberwerkz.com");
        $this->type("id=LoginForm_password", "123");
        $this->clickAndWait("id=submit_login");
        $this->assertTextPresent('Applications');
        $this->click("link=Account");
        $this->waitForPageToLoad("30000");
        $this->assertTextPresent('Security');
        $this->click("id=changeEmail");
        $this->waitForElementPresent("id=ChangeEmailForm_password");
        $this->type("id=ChangeEmailForm_password", "123");
        $this->type("id=ChangeEmailForm_newEmail", "timberwerkzB@timberwerkz.com");
        $this->click("link=Submit");
        $this->clickAndWait("link=Log Out");
        $this->assertTextPresent('Sign Up');
        $this->type("id=LoginForm_email", "timberwerkzB@timberwerkz.com");
        $this->type("id=LoginForm_password", "123");
        $this->clickAndWait("id=submit_login");
        $this->assertTextPresent('Applications');
    }

}

module.exports = {
  '@tags': ['responsive_menu'],
  before(browser) {
    browser.drupalInstall({
      setupFile: __dirname + '/../SiteInstallSetupScript.php',
      installProfile: 'minimal',
    });
  },
  after(browser) {
    browser.drupalUninstall();
  },
  'Confirm that superfish functionality works': browser => {
    browser
      .drupalCreateUser({
        name: 'user',
        password: '123',
        permissions: ['administer site configuration'],
      })
      .drupalLogin({ name: 'user', password: '123' })
      .resizeWindow(1200, 800)
      .drupalRelativeURL('/admin/config/user-interface/responsive-menu')
      .waitForElementVisible('body', 1000)
    browser
      .click('input[id="edit-superfish"]')
      .expect.element('input[id="edit-superfish"]').to.be.selected
    browser
      .submitForm('#responsive-menu-settings')
      .waitForElementVisible('body', 1000)
      .drupalRelativeURL('/node/1')
    browser
      .assert.cssClassPresent('#horizontal-menu', 'sf-js-enabled')
    browser
      .drupalLogAndEnd({ onlyOnError: false });
  },
};

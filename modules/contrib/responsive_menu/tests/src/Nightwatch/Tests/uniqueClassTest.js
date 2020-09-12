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
  'Confirm that the preprocess code adds a unique class to menu items': browser => {
    browser
      .drupalRelativeURL('/node/2')
      .resizeWindow(400, 800)
    browser
      .expect.element('#off-canvas').to.not.be.visible
    browser
      .click('.responsive-menu-toggle-icon')
      .expect.element('#off-canvas').to.be.visible;
    browser
      .expect.element('.mm-listview li').to.have.attribute('class').which.matches(/menu-item--[^\s\\]+/);
    browser
      .drupalLogAndEnd({ onlyOnError: false });
  },
};

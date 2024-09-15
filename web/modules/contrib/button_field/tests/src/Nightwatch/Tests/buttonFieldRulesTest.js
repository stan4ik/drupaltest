module.exports = {
  '@tags': ['button_field'],
  before(browser) {
    const setupFile = `${__dirname}/../../TestSite/ButtonFieldInstallTestScript.php`;
    browser.drupalInstall({ setupFile });
  },
  after(browser) {
    browser.drupalUninstall();
  },
  'Test click button field on node view triggers reaction rule': (browser) => {
    browser
      .drupalCreateUser({
        name: 'sam',
        password: 'sam',
        permissions: [
          'bypass node access',
          'access administration pages',
          'access site reports',
        ],
      })
      .drupalLogin({ name: 'sam', password: 'sam' })
      .drupalRelativeURL('/node/1')
      .assert.visible('.button_field')
      .click('.button_field')
      .pause(500)
      .refresh()
      .assert.containsText('[drupal-data-messages]', 'Button field clicked!')
      .drupalLogAndEnd({ onlyOnError: false });
  },
};

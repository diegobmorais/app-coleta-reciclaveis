module.exports = {
  e2e: {
    baseUrl: 'http://localhost:5180',
    specPattern: 'cypress/e2e/**/*.spec.{js,jsx,ts,tsx}',
    setupNodeEvents(on, config) {
      return config
    },
  },
};

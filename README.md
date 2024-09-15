From project root directory run docker compose up -d or make up to start containers. Give it 10-20 seconds to initialize after the start

Proceed with Drupal installation at http://drupal.docker.localhost:8000

Database at web directory. Import via drush sql-qli < drupal-dump.sql
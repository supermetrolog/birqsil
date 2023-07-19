#!/bin/sh
chmod 777 -R ./frontend/runtime
chmod 777 -R ./backend/runtime

chmod 777 -R ./frontend/web/assets
chmod 777 -R ./backend/web/assets

chmod 777 -R ./backend/web/assets

chmod 777 -R ./backend/tests/_output
chmod 777 -R ./common/tests/_output
chmod 777 -R ./console/migrations
chmod 666 ./composer.json
chmod 666 ./composer.lock
chmod 777 -R ./vendor


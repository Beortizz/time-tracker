FROM serversideup/php:8.3-fpm-apache as base
WORKDIR /var/www/html
COPY composer.json composer.lock ./
RUN composer install --no-dev --no-interaction --no-plugins --no-scripts --prefer-dist

FROM node:20 as static-assets
WORKDIR /app
COPY . .
COPY --from=base /var/www/html .
RUN npm install
RUN npm run build

FROM serversideup/php:8.3-fpm-apache

ENV APP_NAME 'Laravel'
ENV APP_ENV 'production'
ENV APP_KEY ''
ENV APP_DEBUG 'false'
ENV APP_URL 'http://localhost'

ENV LOG_CHANNEL 'stack'
ENV LOG_DEPRECATIONS_CHANNEL 'null'
ENV LOG_LEVEL 'debug'

ENV DB_CONNECTION ''
ENV DB_HOST ''
ENV DB_PORT ''
ENV DB_DATABASE ''
ENV DB_USERNAME ''
ENV DB_PASSWORD ''

ENV BROADCAST_DRIVER 'log'
ENV CACHE_DRIVER 'file'
ENV FILESYSTEM_DISK 'local'
ENV QUEUE_CONNECTION 'sync'
ENV SESSION_DRIVER 'file'
ENV SESSION_LIFETIME 120

ENV MAIL_MAILER ''
ENV MAIL_HOST ''
ENV MAIL_PORT ''
ENV MAIL_USERNAME ''
ENV MAIL_PASSWORD ''
ENV MAIL_ENCRYPTION ''
ENV MAIL_FROM_ADDRESS ''
ENV MAIL_FROM_NAME ''

ENV AWS_ACCESS_KEY_ID ''
ENV AWS_SECRET_ACCESS_KEY ''
ENV AWS_DEFAULT_REGION ''
ENV AWS_BUCKET ''
ENV AWS_USE_PATH_STYLE_ENDPOINT ''

ENV VITE_APP_NAME $APP_NAME
ENV VITE_PUSHER_APP_KEY $PUSHER_APP_KEY
ENV VITE_PUSHER_HOST $PUSHER_HOST
ENV VITE_PUSHER_PORT $PUSHER_PORT
ENV VITE_PUSHER_SCHEME $PUSHER_SCHEME
ENV VITE_PUSHER_APP_CLUSTER $PUSHER_APP_CLUSTER
ENV VUE_APP_BASE_URL 'http://localhost:8000/api'

USER www-data:www-data
WORKDIR /var/www/html
COPY --from=base --chown=www-data:www-data /var/www/html .
COPY --from=static-assets --chown=www-data:www-data /app/public/build ./public/build
COPY --chown=www-data:www-data . .

RUN composer dump-autoload
RUN php artisan route:cache
RUN php artisan view:cache

RUN mkdir -p /usr/local/bin

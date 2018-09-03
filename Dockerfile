FROM php:7.2-cli-alpine

# Include composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

RUN apk add -q --update --no-cache git openssh wget tar

RUN wget https://github.com/redthor/word-finder/archive/1.0.tar.gz -O - |tar --transform "s/word-finder-1.0/wf/" -xz

WORKDIR /wf

RUN mkdir -p ~/.ssh && \
    echo -e "\nHost github.com\n\tStrictHostKeyChecking no\n\n" >> ~/.ssh/config && \
    chmod 600 ~/.ssh/config

ENV COMPOSER_ALLOW_SUPERUSER 1

RUN composer install --prefer-dist -o && rm -rf ~/.composer

ENV APP_ENV dev

ENTRYPOINT ["./bin/console", "server:run", "0.0.0.0:80"]

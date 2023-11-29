FROM ${AWS_ACCOUNT_ID_SHAREDCOREECR}.dkr.ecr.${AWS_REGION}.amazonaws.com/php-base:7.4.33-fpm-alpine-f49382c
LABEL maintainer="shaun.hare@dvsa.gov.uk"
LABEL description="PHP Alpine base image with dependency packages"
LABEL Name="ssuivol-php-fpm:7.4.33-alpine-fpm"
LABEL Version="0.1"


# Expose ports
EXPOSE 80


RUN apk -U upgrade && apk add --no-cache \
    curl \
    nginx \
    clamav
    
COPY nginx/conf.d/frontend.conf /etc/nginx/nginx.conf

COPY php-fpm/php-fpm.d/www.conf /usr/local/etc/php-fpm.d/www.conf

RUN mkdir -p /opt/dvsa/olcs-internal/public/static /var/log/dvsa /tmp/Entity/Proxy && \
    touch /var/log/dvsa/internal.log
    
ADD iuweb.tar.gz /opt/dvsa/olcs-internal

COPY static /opt/dvsa/olcs-internal/public/static

COPY start.sh /start.sh
RUN chmod +x /start.sh


RUN freshclam

RUN adduser clamav nginx  && \
    rm -f /opt/dvsa/olcs-internal/config/autoload/local* && \
    mkdir /var/nginx && \
    mkdir /var/tmp/nginx && \
    mkdir /run/clamav && chown clamav:clamav /run/clamav && chmod 1777 /run/clamav && \
    chown -R nginx:nginx /opt/dvsa /tmp/* /var/log/dvsa /var/nginx /var/tmp/nginx && \
    chmod u=rwx,g=rwx,o=r -R /opt/dvsa /tmp/* /var/log/dvsa /var/nginx /var/tmp/nginx && \
    touch /run/clamav/clamd.sock && touch /run/clamav/clamd.pid && \
    chmod 1777 /run/clamav/clamd.pid  /run/clamav/clamd.sock


CMD ["/start.sh"]

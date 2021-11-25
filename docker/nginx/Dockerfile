FROM nginx:alpine

RUN apk update && apk add mc

COPY /nginx.conf /etc/nginx/conf.d/default.conf

COPY /default-ssl /ssl
COPY /ssl /ssl

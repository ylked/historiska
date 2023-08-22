#!/bin/sh

img_name=ylked/historiska-laravel
docker buildx build . --tag $img_name --platform linux/arm64,linux/amd64 --push
docker pull $img_name

起動：
```
docker run --rm -it -w=/app/public -v ${PWD}:/app -p 8000:8000 php:8.2-alpine php -S 0.0.0.0:8000
```

確認：
```
curl localhost:8000
```
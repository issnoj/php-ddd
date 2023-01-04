```
docker run --rm -it -w=/app/public -v ${PWD}:/app -p 8000:8000 php:8.2-alpine php -S 0.0.0.0:8000
```

```
docker run --rm -it -w=/work/public -v ${PWD}:/work php:8.2-alpine php index.php
```